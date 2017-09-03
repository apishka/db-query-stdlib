<?php

namespace ApishkaTest\DbQuery\StdLib;

use Apishka\DbQuery\StdLib\Expression;

/**
 * Expression test
 */

class ExpressionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Get field
     *
     * @param string $name
     *
     * @return Field
     */

    protected function getExpression($name)
    {
        return new Expression($name);
    }

    /**
     * Test build where equals
     *
     * @dataProvider providerTestGetValue
     *
     * @param string $expected
     * @param mixed  $value
     */

    public function testGetValue($expected, $value)
    {
        $this->assertSame(
            $expected,
            $this->getExpression($value)->getValue()
        );
    }

    /**
     * Provider test get value
     *
     * @return array
     */

    public function providerTestGetValue()
    {
        return array(
            ['NOW()', 'NOW()'],
        );
    }
}
