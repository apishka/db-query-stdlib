<?php

namespace Apishka\DbQuery;

/**
 * Query set field trait
 */

trait QuerySetFieldTrait
{
    /**
     * Set fields
     *
     * @var array
     */

    protected $_set_fields = array();

    /**
     * Set
     *
     * Query set(string $field [, $operation], $value)
     *
     * ->set('field1', 'value1')
     * ->set('field2', '+', 10)
     * ->set('field3', null)
     *
     * @param string $field
     * @param string $operation
     * @param mixed  $value
     *
     * @return Query this
     */

    public function set($field)
    {
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
                throw new \BadMethodCallException('Only 2 and 3 arguments possible to this function');
        }

        $operation = $this->normalizeSetOperation($operation);
        $key = $this->prepareSetKey($field, $operation, $value);

        if (isset($this->_set_fields[$key]))
            throw new \LogicException('Restriction on ' . var_export($field, true) . ' already exists in set condition');

        $data = array(
            'field'     => $field,
            'operation' => $operation,
            'value'     => $value,
        );

        $key
            ? $this->_set_fields[$key] = $data
            : $this->_set_fields[] = $data
        ;

        return $this;
    }

    /**
     * Normalize set operation
     *
     * @param string|null $operation
     *
     * @return string
     */

    protected function normalizeSetOperation($operation)
    {
        if ($operation === null)
            return '=';

        return strtolower($operation);
    }

    /**
     * Prepare set key
     *
     * @param string $field
     * @param string $operation
     * @param mixed  $value
     *
     * @return string|null
     */

    public function prepareSetKey($field, $operation, $value)
    {
        return $field;
    }
}
