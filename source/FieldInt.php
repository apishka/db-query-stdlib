<?php

namespace Apishka\DbQuery\StdLib;

use Apishka\Singleton\Manager;

/**
 * Integer field type
 *
 * @easy-extend-base
 */

class FieldInt extends FieldAbstract
{
    /**
     * Get default transformations
     *
     * @return array
     */

    protected function getDefaultTransformations()
    {
        $transformations = array();

        $transformations['Transform/Int'] = [];

        return $transformations;
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
            return parent::quote($query, $value);

        return Manager::getInstance()->validator->validate(
            $value,
            $this->getTransformations()
        );
    }
}
