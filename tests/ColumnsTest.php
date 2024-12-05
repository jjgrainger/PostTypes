<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Column;
use PostTypes\Columns;

class ColumnsTest extends TestCase
{
    public function test_can_add_column()
    {
        $columns = new Columns;

        $columns->add('column', 'Test Column');

        $this->assertArrayHasKey('column', $columns->add);
        $this->assertSame('Test Column', $columns->add['column']);
    }

    public function test_can_add_column_with_column_class()
    {
        $stub = $this->getMockForAbstractClass(Column::class);

        $stub->expects($this->any())
             ->method('name')
             ->will($this->returnValue('column'));

        $columns = new Columns;
        $columns->column($stub);

        $this->assertArrayHasKey('column', $columns->add);
        $this->assertSame('Column', $columns->add['column']);

        $this->assertArrayHasKey('column', $columns->populate);
        $this->assertIsCallable($columns->populate['column']);
    }

    public function test_can_add_column_with_populate_callback()
    {
        $columns = new Columns;

        $columns->add('column', 'Test Column', function() {});

        $this->assertArrayHasKey('column', $columns->populate);
        $this->assertIsCallable($columns->populate['column']);
    }

    public function test_can_set_column_populate_callback()
    {
        $columns = new Columns;

        $columns->populate('column', function() {});

        $this->assertArrayHasKey('column', $columns->populate);
        $this->assertIsCallable($columns->populate['column']);
    }

    public function test_can_set_remove_column()
    {
        $columns = new Columns;

        $columns->remove(['column']);

        $this->assertEquals(['column'], $columns->remove);
    }

    public function test_can_set_remove_columns_with_multiple_calls()
    {
        $columns = new Columns;

        $columns->remove(['column']);
        $columns->remove(['column_2']);

        $this->assertEquals(['column', 'column_2'], $columns->remove);
    }

    public function test_can_set_order_columns()
    {
        $columns = new Columns;

        $columns->order([
            'column' => 1,
        ]);

        $this->assertEquals(['column' => 1], $columns->order);
    }

    public function test_can_set_order_columns_with_multiple_calls()
    {
        $columns = new Columns;

        $columns->order(['column' => 1]);
        $columns->order(['column_2' => 3]);

        $this->assertEquals(['column' => 1, 'column_2' => 3], $columns->order);
    }

    public function test_can_order_column_with_column_class()
    {
        $columns = new Columns;

        $stub = $this->createMock(Column::class);

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('column'));

        $stub->expects($this->any())
            ->method('order')
            ->will($this->returnValue(1));


        $columns->column($stub);

        $this->assertEquals(['column' => 1], $columns->order);
    }

    public function test_can_set_sortable_column()
    {
        $columns = new Columns;

        $columns->sortable('column', function() {});

        $this->assertArrayHasKey('column', $columns->sortable);
        $this->assertIsCallable($columns->sortable['column']);
    }

    public function test_can_apply_columns()
    {
        $columns = new Columns;

        $columns->add('column_5', 'Column 5');

        $columns->remove(['column_2']);

        $columns->order([
            'column_3' => 0,
        ]);

        $original = [
            'column_1' => 'Column 1',
            'column_2' => 'Column 2',
            'column_3' => 'Column 3',
            'column_4' => 'Column 4',
        ];

        $modified = $columns->applyColumns($original);

        $expected = [
            'column_3' => 'Column 3',
            'column_1' => 'Column 1',
            'column_4' => 'Column 4',
            'column_5' => 'Column 5',
        ];

        $this->assertSame($expected, $modified);
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
            ->with($this->greaterThan(0));

        $columns->column($stub);

        $columns->populateColumn('column', [1]);
    }

    public function test_can_add_sortable_columns_to_sortable_list()
    {
        $columns = new Columns;

        $columns->sortable('column', function() {});

        $sortable = [
            'title' => 'title',
        ];

        $sortable = $columns->setSortable($sortable);

        $expected = [
            'title' => 'title',
            'column' => 'column',
        ];

        $this->assertSame($expected, $sortable);
    }

    public function test_can_sort_column()
    {
        $columns = new Columns;

        $stub = $this->createMock(Column::class);

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('column'));

        $stub->expects($this->once())
            ->method('isSortable')
            ->will($this->returnValue(true));

        $stub->expects($this->once())
            ->method('sort')
            ->with($this->greaterThan(0));

        $columns->column($stub);

        $columns->sortColumn('column', 1);
    }
}
