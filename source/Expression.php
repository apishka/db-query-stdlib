<?php

namespace Apishka\DbQuery;

/**
 * Expression
 */

class Expression
{
    /**
     * Traits
     */

    use \Apishka\EasyExtend\Helper\ByClassNameTrait;

    /**
     * Value
     *
     * @var string
     */

    private $_value = null;

    /**
     * Construct
     *
     * @param string $value
     *
     * @return FieldAbstract
     */

    public function __construct($value)
    {
        $this->_value = $value;
    }

    /**
     * Get value
     *
     * @return string
     */

    public function getValue()
    {
        return $this->_value;
    }
}
