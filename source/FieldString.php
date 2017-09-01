<?php

namespace Apishka\DbQuery;

use Apishka\Singleton\Manager;

/**
 * String field type
 *
 * @easy-extend-base
 */

class FieldString extends FieldAbstract
{
    /**
     * Get default transformations
     *
     * @return array
     */

    protected function getDefaultTransformations()
    {
        $transformations = array();

        $transformations['Transform/String'] = [];

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

        return $query->quote(
            Manager::getInstance()->validator->validate(
                $value,
                $this->getTransformations()
            )
        );
    }
}
