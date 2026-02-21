<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Column;
use PostTypes\Columns;
use PostTypes\Integrations\ManageTaxonomyColumns;
use PostTypes\Taxonomy;

class ManageTaxonomyColumnsTest extends TestCase
{
    public function test_can_modify_columns()
    {
        $defaults = [
            'cb' => '',
            'name' => 'Name',
        ];

        $columns = new Columns;
        $columns->label('popularity', 'Popularity');

        $stub = $this->getMockBuilder(Taxonomy::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('genre'));

        $stub->expects($this->once())
            ->method('columns')
            ->will($this->returnValue($columns));

        $registrar = new ManageTaxonomyColumns($stub);
        $registrar->createColumns();
        $output = $registrar->modifyColumns($defaults);

        $expected = [
            'cb' => '',
            'name' => 'Name',
            'popularity' => 'Popularity',
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

        $stub = $this->getMockBuilder(Taxonomy::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('genre'));

        $stub->expects($this->once())
            ->method('columns')
            ->will($this->returnValue($columns));

        $registrar = new ManageTaxonomyColumns($stub);
        $registrar->createColumns();
        $registrar->populateColumns('', 'column', 1);
    }

    public function test_can_set_sortable_columns()
    {
        $columns = new Columns;
        $columns->sort('column', function() {});

        $sortable = [
            'title' => 'title',
        ];

        $stub = $this->getMockBuilder(Taxonomy::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('genre'));

        $stub->expects($this->once())
            ->method('columns')
            ->will($this->returnValue($columns));

        $registrar = new ManageTaxonomyColumns($stub);
        $registrar->createColumns();
        $output = $registrar->setSortableColumns($sortable);

        $expected = [
            'title' => 'title',
            'column' => 'column',
        ];

        $this->assertEquals($expected, $output);
    }
}
