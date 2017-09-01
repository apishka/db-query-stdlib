<?php

namespace Apishka\DbQuery\StdLib;

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
