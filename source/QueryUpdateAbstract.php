<?php

namespace Apishka\DbQuery;

/**
 * Query update abstract
 */

abstract class QueryUpdateAbstract extends QueryAbstract
{
    /**
     * Traits
     */

    use QueryWhereTrait;
    use QuerySetFieldTrait;
}
