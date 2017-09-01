<?php

namespace Apishka\DbQuery;

/**
 * Query where trait
 */

trait QueryWhereTrait
{
    /**
     * Where options
     *
     * @var array
     */

    protected $_where = array();

    /**
     * Operation to key
     *
     * @var array
     */

    protected $_operation2key = array(
        '='  => '=',
        '>'  => '>',
        '<'  => '<',
        '>=' => '>',
        '<=' => '<',
    );

    /**
     * Where
     *
     * Query where(string $field [, $operation], $value)
     *
     * ->where('param1', 'value')
     * ->where('param2', '>', 'value')
     * ->where('param3', null)
     *
     * @param string $field
     *
     * @return Query this
     */

    public function where($field)
    {
        if ($field instanceof self)
        {
            $this->_where[] = array(
                'query' => $field,
            );

            return $this;
        }

        switch (func_num_args())
        {
            case 2:
                $operation = '=';
                $value     = func_get_arg(1);
                break;

            case 3:
                $operation = func_get_arg(1);
                $value     = func_get_arg(2);
                break;

            default:
                throw new \BadMethodCallException('Only 2 or 3 arguments possible to this function');
        }

        $operation = $this->normalizeWhereOperation($operation);
        $key = $this->prepareWhereKey($field, $operation, $value);

        if (isset($this->_where[$key]))
            throw new \LogicException('Restriction on ' . var_export($field, true) . ' already exists in where condition');

        $data = array(
            'field'     => $field,
            'operation' => $operation,
            'value'     => $value,
        );

        $key
            ? $this->_where[$key] = $data
            : $this->_where[] = $data
        ;

        return $this;
    }

    /**
     * Where or
     *
     * @param Query $query
     *
     * @return Query this
     */

    public function whereOr($query)
    {
        $this->_where[] = array(
            'query' => $query,
            'glue'  => 'or',
        );

        return $this;
    }

    /**
     * Normalize where operation
     *
     * @param string|null $operation
     *
     * @return string
     */

    protected function normalizeWhereOperation($operation)
    {
        if ($operation === null)
            return '=';

        if ($operation === '!=')
            return '<>';

        return strtolower($operation);
    }

    /**
     * Prepare where key
     *
     * @param string $field
     * @param string $operation
     * @param mixed  $value
     *
     * @return string|null
     */

    protected function prepareWhereKey($field, $operation, $value)
    {
        $operation_key = $this->getWhereOperationKey($operation);
        if ($operation_key)
            return $field . $operation_key;

        if (is_bool($value))
            return sha1($field . $operation . intval($value));

        if (is_scalar($value))
            return sha1($field . $operation . $value);

        return;
    }

    /**
     * Get where operation key
     *
     * @param string $operation
     */

    protected function getWhereOperationKey($operation)
    {
        if (array_key_exists($operation, $this->_operation2key))
            return $this->_operation2key[$operation];

        return;
    }
}
