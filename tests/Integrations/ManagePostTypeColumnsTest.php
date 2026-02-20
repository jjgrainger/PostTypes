<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Column;
use PostTypes\Columns;
use PostTypes\Integrations\ManagePostTypeColumns;
use PostTypes\PostType;

class ManagePostTypeColumnsTest extends TestCase
{
    public function test_can_modify_columns()
    {
        $defaults = [
            'cb' => '',
            'title' => 'Title',
            'author' => 'Author',
        ];

        $columns = new Columns;
        $columns->label('date', 'Date');

        $stub = $this->getMockBuilder(PostType::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('book'));

        $stub->expects($this->once())
            ->method('columns')
            ->will($this->returnValue($columns));

        $registrar = new ManagePostTypeColumns($stub);
        $registrar->createColumns();
        $output = $registrar->modifyColumns($defaults);

        $expected = [
            'cb' => '',
            'title' => 'Title',
            'author' => 'Author',
            'date' => 'Date',
        ];

        $this->assertEquals($expected, $output);
    }

    public function test_can_populate_column()
    {
        $columns = new Columns;

        $stub = $this->createMock(Column::class);

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('column'));

        $stub->expects($this->once())
            ->method('populate')
            ->willReturnCallback(function() {});

        $columns->column($stub);

        $stub = $this->getMockBuilder(PostType::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('book'));

        $stub->expects($this->once())
            ->method('columns')
            ->will($this->returnValue($columns));

        $registrar = new ManagePostTypeColumns($stub);
        $registrar->createColumns();
        $registrar->populateColumns('column', 1);
    }

    public function test_can_set_sortable_columns()
    {
        $columns = new Columns;
        $columns->sort('column', function() {});

        $sortable = [
            'title' => 'title',
        ];

        $stub = $this->getMockBuilder(PostType::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('book'));

        $stub->expects($this->once())
            ->method('columns')
            ->will($this->returnValue($columns));

        $registrar = new ManagePostTypeColumns($stub);
        $registrar->createColumns();
        $output = $registrar->setSortableColumns($sortable);

        $expected = [
            'title' => 'title',
            'column' => 'column',
        ];

        $this->assertEquals($expected, $output);
    }
}
