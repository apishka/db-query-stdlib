<?php

namespace Apishka\DbQuery\StdLib;

/**
 * Field
 */

class FieldAbstract
{
    /**
     * Traits
     */

    use \Apishka\EasyExtend\Helper\ByClassNameTrait;

    /**
     * Where operation to function
     *
     * @var array
     */

    private $_build_where = null;

    /**
     * Update operation to function
     *
     * @var array
     */

    private $_build_update = null;

    /**
     * Insert operation to function
     *
     * @var array
     */

    private $_build_insert = null;

    /**
     * Name
     *
     * @var string
     */

    private $_name = null;

    /**
     * Transformations
     *
     * @var array
     */

    private $_transformations = null;

    /**
     * Construct
     *
     * @param string $name
     *
     * @return FieldAbstract
     */

    public function __construct($name)
    {
        $this->_name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */

    public function getName()
    {
        return $this->_name;
    }

    /**
     * Get transformations
     *
     * @return array
     */

    public function getTransformations()
    {
        $this->prepareTransformations();

        return $this->_transformations;
    }

    /**
     * Prepare transformations
     */

    protected function prepareTransformations()
    {
        if ($this->_transformations === null)
            $this->_transformations = $this->getDefaultTransformations();
    }

    /**
     * Prepare build where functions
     *
     * @param array $build_where
     *
     * @return array
     */

    protected function prepareBuildWhereFunctions(array $build_where)
    {
        return $build_where;
    }

    /**
     * Get build where function
     *
     * @param string $operation
     *
     * @return string
     */

    protected function getBuildWhereFunction($operation)
    {
        if ($this->_build_where === null)
            $this->_build_where = $this->prepareBuildWhereFunctions($this->_build_where ?? array());

        return $this->_build_where[$operation] ?? null;
    }

    /**
     * Build where
     *
     * @param QueryAbstract $query
     * @param array         $where
     *
     * @return string
     */

    public function buildWhere(QueryAbstract $query, array $where)
    {
        if (!array_key_exists('operation', $where))
            throw new \BadMethodCallException('Key "operation" not found in $where array');

        $function = $this->getBuildWhereFunction($where['operation']);
        if (!$function)
            throw new \BadMethodCallException('Finction for operation ' . var_export($where['operation'], true) . ' not exists');

        return $this->$function($query, $where);
    }

    /**
     * Prepare build update functions
     *
     * @param array $build_update
     *
     * @return array
     */

    protected function prepareBuildUpdateFunctions(array $build_update)
    {
        return $build_update;
    }

    /**
     * Get build where function
     *
     * @param string $operation
     *
     * @return array
     */

    protected function getBuildUpdateFunction($operation)
    {
        if ($this->_build_update === null)
            $this->_build_update = $this->prepareBuildUpdateFunctions($this->_build_update ?? array());

        return $this->_build_update[$operation] ?? null;
    }

    /**
     * Build update
     *
     * @param QueryAbstract $query
     * @param array         $update
     *
     * @return string
     */

    public function buildUpdate(QueryAbstract $query, array $update)
    {
        if (!array_key_exists('operation', $update))
            throw new \BadMethodCallException('Key "operation" not found in $update array');

        $function = $this->getBuildUpdateFunction($update['operation']);
        if (!$function)
            throw new \BadMethodCallException('Finction for operation ' . var_export($update['operation'], true) . ' not exists');

        return $this->$function($query, $update);
    }

    /**
     * Prepare build insert functions
     *
     * @param array $build_insert
     *
     * @return array
     */

    protected function prepareBuildInsertFunctions(array $build_insert)
    {
        return $build_insert;
    }

    /**
     * Get build where function
     *
     * @param string $operation
     *
     * @return array
     */

    protected function getBuildInsertFunction($operation)
    {
        if ($this->_build_insert === null)
            $this->_build_insert = $this->prepareBuildInsertFunctions($this->_build_insert ?? array());

        return $this->_build_insert[$operation] ?? null;
    }

    /**
     * Build insert
     *
     * @param Query $query
     * @param array $insert
     *
     * @return string
     */

    public function buildInsert($query, array $insert)
    {
        if (!array_key_exists('operation', $insert))
            throw new \BadMethodCallException('Key "operation" not found in $insert array');

        $function = $this->getBuildInsertFunction($insert['operation']);
        if (!$function)
            throw new \BadMethodCallException('Finction for operation ' . var_export($insert['operation'], true) . ' not exists');

        return $this->$function($query, $insert);
    }

    /**
     * Quote
     *
     * @param QueryAbstract $query
     * @param mixed         $value
     *
     * @return string
     */

    public function quote(QueryAbstract $query, $value)
    {
        if ($this->isExpression($value))
            return $value->getValue();

        throw new \BadMethodCallException('Method for ' . var_export(gettype($value), true) . ' not defined');
    }

    /**
     * Is expression
     *
     * @param mixed $value
     *
     * @return bool
     */

    public function isExpression($value)
    {
        return $value instanceof Expression;
    }
}
