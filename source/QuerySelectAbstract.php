<?php

namespace Apishka\DbQuery;

/**
 * Query select abstract
 */

abstract class QuerySelectAbstract extends QueryAbstract
{
    /**
     * Traits
     */

    use QueryWhereTrait;

    /**
     * Relation constants
     */

    const RELATION_JOIN = 'join';

    /**
     * Group by
     *
     * @var array
     */

    protected $_group_by = array();

    /**
     * Limit
     *
     * @var array
     */

    protected $_limit = array();

    /**
     * Join on
     *
     * @var array
     */

    protected $_join_on = array();

    /**
     * Order by
     *
     * @var array
     */

    protected $_order_by = array();

    /**
     * Relations
     *
     * @var array
     */

    protected $_relations = array();

    /**
     * Fields
     *
     * @var array
     */

    protected $_fields = null;

    /**
     * Fields exclude
     *
     * @var array
     */

    protected $_fields_exclude = array();

    /**
     * Sort normalize
     *
     * @var array
     */

    protected $_sort_normalize = array(
        '+'     => 'asc',
        '-'     => 'desc',
    );

    /**
     * Sort
     *
     * @param string $field
     * @param string $sort
     * @param int    $position
     *
     * @return Query
     */

    public function orderBy($field, $sort, $position = null)
    {
        $sort = $this->normalizeSort($sort);
        $key = $this->prepareOrderByKey($field);

        if (isset($this->_order_by[$key]))
            throw new \LogicException('Restriction on ' . var_export($field, true) . ' already exists in order by');

        $this->_order_by[$key] = array(
            'field'    => $field,
            'sort'     => $sort,
            'position' => $position,
        );

        return $this;
    }

    /**
     * Normalize sort
     *
     * @param string $sort
     *
     * @return string
     */

    protected function normalizeSort($sort)
    {
        if (array_key_exists($sort, $this->_sort_normalize))
            $sort = $this->_sort_normalize[$sort];

        return strtolower($sort);
    }

    /**
     * Prepare sort key
     *
     * @param string $field
     *
     * @return string
     */

    protected function prepareOrderByKey($field)
    {
        return $field;
    }

    /**
     * Group by
     *
     * @param string $field
     *
     * @return Query this
     */

    public function groupBy($field)
    {
        $key = $field;

        if (isset($this->_group_by[$key]))
            throw new \LogicException('Restriction on ' . var_export($field, true) . ' already exists in group by condition');

        $this->_group_by[$key] = array(
            'field' => $field,
        );

        return $this;
    }

    /**
     * Join
     *
     * @param Query|string $relation
     * @param array        $settings
     * @param Query|null   $extra
     *
     * @return Query
     */

    public function join($relation, array $settings = array(), Query $extra = null)
    {
        if (is_string($relation))
            throw new \LogicException('Not implemented yet');

        $query = $relation;

        $relation_key = $query->getAliasName() ?? $query->getTableName();

        if (!$relation_key)
            throw new \LogicException('Cannot determine relation key');

        if (array_key_exists($relation_key, $this->_relations))
            throw new \LogicException('Relation ' . var_export($relation_key, true) . ' already registered');

        $this->_relations[$relation_key] = array(
            'query'    => $query,
            'type'     => self::RELATION_JOIN,
            'settings' => $settings,
        );

        return $this;
    }

    /**
     * Join on
     *
     * @param string $field_from
     * @param string $field_to
     *
     * @return Query this
     */

    public function joinOn($field_from, $field_to)
    {
        if (isset($this->_join_on[$field_from]))
            throw new \LogicException('Join on condition for ' . var_export($field_from, true) . ' is already set');

        $this->_join_on[$field_from] = array(
            'field_from' => $field_from,
            'field_to'   => $field_to,
        );

        return $this;
    }

    /**
     * Get join on
     *
     * @return array
     */

    public function getJoinOn()
    {
        return $this->_join_on ?? array();
    }

    /**
     * Limit
     *
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return Query this
     */

    public function limit($offset, $limit)
    {
        if ($this->_limit)
            throw new \LogicException('Limit and offset already set');

        $this->_limit = array(
            'limit'  => $limit,
            'offset' => $offset,
        );

        return $this;
    }

    /**
     * Fields
     *
     * @return array
     */

    public function fields()
    {
        $this->registerFields(false);

        unset($this->_fields['*']);

        foreach ($this->_fields as $field => $details)
        {
            if (array_key_exists('include', $details) && $details['include'] === true)
                throw new \LogicException('Fields list already set');
        }

        foreach (func_get_args() as $field)
        {
            if (!is_string($field))
                throw new \LogicException('Field ' . var_export($field, true) . ' should be string');

            if (array_key_exists($field, $this->_fields))
                throw new \LogicException('Fields ' . var_export($field, true) . ' already in fields list');

            $this->_fields[$field] = array(
                'include' => true,
                'field'   => $field,
            );
        }

        return $this;
    }

    /**
     * Add field
     *
     * @param string $field
     * @param string $alias
     *
     * @return Query this
     */

    public function addField($field, $alias)
    {
        if ($this->_fields && array_key_exists($alias, $this->_fields))
            throw new \LogicException('Alias ' . var_export($alias, true) . ' already exists in fields list');

        $this->registerFields(true);

        $this->_fields[$alias] = array(
            'add'   => true,
            'field' => $field,
            'alias' => $alias,
        );

        return $this;
    }

    /**
     * Exclude field
     *
     * @param string $field
     *
     * @return Query this
     */

    public function excludeField($field)
    {
        if (!$this->_fields_exclude)
            $this->_fields_exclude = array();

        if (array_key_exists($field, $this->_fields_exclude))
            throw new \LogicException('Field ' . var_export($field, true) . ' already in exclude fields list');

        $this->_fields_exclude[$field] = array(
            'exclude' => true,
            'field'   => $field,
        );

        return $this;
    }

    /**
     * Register fields
     *
     * @param bool $star
     *
     * @return Query
     */

    protected function registerFields($star)
    {
        if ($this->_fields === null)
        {
            $this->_fields = array();

            if ($star)
                $this->_fields['*'] = array();
        }

        return $this;
    }
}
