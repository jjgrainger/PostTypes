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
        $this->assertEquals(null, $stub->populate(1));
        $this->assertEquals(null, $stub->order());
        $this->assertEquals(null, $stub->sort(true));
        $this->assertEquals(false, $stub->isSortable());
    }
}
