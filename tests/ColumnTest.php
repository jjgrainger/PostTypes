<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Column;

class ColumnTest extends TestCase
{
    public function test_column_returns_defaults()
    {
        $stub = $this->getMockForAbstractClass(Column::class);

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('price'));

        $this->assertEquals('price', $stub->name());
        $this->assertEquals('Price', $stub->label());
        $this->assertEquals(null, $stub->populate());
        $this->assertEquals(null, $stub->position());
        $this->assertEquals(null, $stub->sort());
    }
}
