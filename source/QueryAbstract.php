<?php

namespace Apishka\DbQuery\StdLib;

/**
 * Query abstract
 */

abstract class QueryAbstract
{
    /**
     * Traits
     */

    use \Apishka\EasyExtend\Helper\ByClassNameTrait;

    /**
     * Fields
     *
     * @var array
     */

    protected $_structure_fields = array();

    /**
     * To string
     *
     * @return string
     */

    public function __toString()
    {
        return $this->build();
    }

    /**
     * Build
     *
     * @return string
     */

    abstract public function build();

    /**
     * Get field
     *
     * @param string $name
     *
     * @return FieldAbstract
     */

    public function getField($name)
    {
        if (!$this->hasField($name))
            throw new \LogicException('Field ' . var_export($name, true) . ' not registered');

        return $this->_structure_fields[$name];
    }

    /**
     * Has field
     *
     * @param string $name
     *
     * @return bool
     */

    public function hasField($name)
    {
        return $this->isFieldRegistered($name) || $this->isFieldRegistedInModel($name);
    }

    /**
     * Is field registed in model
     *
     * @param string $name
     *
     * @return bool
     */

    public function isFieldRegistedInModel($name)
    {
        return false;
    }

    /**
     * Has field
     *
     * @param string $name
     *
     * @return bool
     */

    public function isFieldRegistered($name)
    {
        return array_key_exists($name, $this->_structure_fields);
    }

    /**
     * Register field
     *
     * @param FieldAbstract $field
     *
     * @return QueryAbstract this
     */

    public function registerField(FieldAbstract $field)
    {
        if ($this->isFieldRegistered($field->getName()))
            throw new \LogicException('Field ' . var_export($field, true) . ' already registered');

        $this->_structure_fields[$field->getName()] = $field;

        return $this;
    }
}
