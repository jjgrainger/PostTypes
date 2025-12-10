<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Columns;
use PostTypes\ColumnBuilder;
use PostTypes\Contracts\ColumnContract;

class ColumnsTest extends TestCase
{
    public function test_can_add_column()
    {
        $columns = new Columns;

        $columns->add('column', 'Test Column');

        $output = $columns->getColumns();

        $this->assertArrayHasKey('column', $output);
        $this->assertSame('Test Column', $output['column']);
    }

    public function test_can_add_column_with_column_class()
    {
        $stub = $this->createMock(ColumnContract::class);

        $stub->method('name')->willReturn('column');
        $stub->method('label')->willReturn('Column');
        $stub->method('position')->willReturn(['after', 'title']);
        $stub->method('isSortable')->willReturn(true);
        $stub->method('sort')->willReturnCallback(function () {});

        $columns = new Columns;
        $columns->column($stub);

        $output = $columns->getColumns();
        $positions = $columns->getPositions();
        $sortable = $columns->getSortCallback('column');

        $this->assertArrayHasKey('column', $output);
        $this->assertSame('Column', $output['column']);

        $this->assertArrayHasKey('column', $positions);
        $this->assertSame(['after', 'title'], $positions['column']);

        $this->assertIsCallable($sortable);
    }

    public function test_create_returns_column_builder()
    {
        $columns = new Columns;

        $builder = $columns->create('new_column');

        $this->assertInstanceOf(ColumnBuilder::class, $builder);
    }

    public function test_modify_returns_column_builder()
    {
        $columns = new Columns;

        $builder = $columns->modify('existing');

        $this->assertInstanceOf(ColumnBuilder::class, $builder);
    }

    public function test_can_set_column_populate_callback()
    {
        $columns = new Columns;

        $callback = function () {};
        $columns->populate('column', $callback);

        $this->assertSame($callback, $columns->getPopulateCallback('column'));
    }

    public function test_get_populate_callback_returns_null_for_missing_key()
    {
        $columns = new Columns;

        $this->assertNull($columns->getPopulateCallback('missing'));
    }

    public function test_can_set_remove_column()
    {
        $columns = new Columns;

        $columns->remove(['column']);

        $this->assertEquals(['column'], $columns->getRemoved());
    }

    public function test_can_set_remove_columns_with_multiple_calls()
    {
        $columns = new Columns;

        $columns->remove(['column']);
        $columns->remove(['column_2']);

        $this->assertEquals(['column', 'column_2'], $columns->getRemoved());
    }

    public function test_can_set_only_columns()
    {
        $columns = new Columns;

        $columns->only(['one']);
        $columns->only(['two']);

        $this->assertEquals(['one', 'two'], $columns->getOnly());
    }

    public function test_can_set_position_after()
    {
        $columns = new Columns;

        $columns->position('col', 'after', 'title');

        $this->assertSame(['after', 'title'], $columns->getPositions()['col']);
    }

    public function test_can_set_position_before()
    {
        $columns = new Columns;

        $columns->position('col', 'before', 'date');

        $this->assertSame(['before', 'date'], $columns->getPositions()['col']);
    }

    public function test_position_throws_exception_for_invalid_direction()
    {
        $this->expectException(InvalidArgumentException::class);

        $columns = new Columns;
        $columns->position('col', 'sideways', 'title');
    }

    public function test_can_set_sortable_column()
    {
        $columns = new Columns;

        $callback = function () {};
        $columns->sort('column', $callback);

        $this->assertSame($callback, $columns->getSortCallback('column'));
    }

    public function test_get_sortable_columns_returns_keys_mapped_to_keys()
    {
        $columns = new Columns;

        $columns->sort('a', function () {});
        $columns->sort('b', function () {});

        $this->assertSame(['a' => 'a', 'b' => 'b'], $columns->getSortableColumns());
    }

    public function test_get_sortable_callback_returns_null_for_missing_key()
    {
        $columns = new Columns;

        $this->assertNull($columns->getSortCallback('missing'));
    }

    public function test_populate_does_not_affect_sort_callbacks()
    {
        $columns = new Columns;

        $columns->populate('col', function () {});

        $this->assertNull($columns->getSortCallback('col'));
    }

    public function test_sort_does_not_affect_populate_callbacks()
    {
        $columns = new Columns;

        $columns->sort('col', function () {});

        $this->assertNull($columns->getPopulateCallback('col'));
    }

    public function test_add_does_not_overwrite_callback_data()
    {
        $columns = new Columns;

        $columns->populate('col', function () {});
        $columns->sort('col', function () {});
        $columns->add('col', 'Label');

        // Callbacks remain unchanged
        $this->assertIsCallable($columns->getPopulateCallback('col'));
        $this->assertIsCallable($columns->getSortCallback('col'));
    }
}
