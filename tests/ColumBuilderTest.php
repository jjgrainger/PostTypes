<?php

use PHPUnit\Framework\TestCase;
use PostTypes\ColumnBuilder;
use PostTypes\Columns;

class ColumnBuilderTest extends TestCase
{
    public function test_label_sets_column_label()
    {
        $columns = $this->createMock(Columns::class);

        $columns->expects($this->once())
                ->method('label')
                ->with('price', 'Price Label');

        $builder = new ColumnBuilder($columns, 'price');

        $result = $builder->label('Price Label');

        $this->assertSame($builder, $result);
    }

    public function test_position_sets_position_correctly()
    {
        $columns = $this->createMock(Columns::class);

        $columns->expects($this->once())
                ->method('position')
                ->with('price', 'after', 'title');

        $builder = new ColumnBuilder($columns, 'price');

        $result = $builder->position('after', 'title');

        $this->assertSame($builder, $result);
    }

    public function test_after_sets_position_after_reference()
    {
        $columns = $this->createMock(Columns::class);

        $columns->expects($this->once())
                ->method('position')
                ->with('price', 'after', 'title');

        $builder = new ColumnBuilder($columns, 'price');

        $result = $builder->after('title');

        $this->assertSame($builder, $result);
    }

    public function test_before_sets_position_before_reference()
    {
        $columns = $this->createMock(Columns::class);

        $columns->expects($this->once())
                ->method('position')
                ->with('price', 'before', 'title');

        $builder = new ColumnBuilder($columns, 'price');

        $result = $builder->before('title');

        $this->assertSame($builder, $result);
    }

    public function test_populate_sets_populate_callback()
    {
        $callback = function () {};

        $columns = $this->createMock(Columns::class);

        $columns->expects($this->once())
                ->method('populate')
                ->with('price', $callback);

        $builder = new ColumnBuilder($columns, 'price');

        $result = $builder->populate($callback);

        $this->assertSame($builder, $result);
    }

    public function test_sort_sets_sort_callback()
    {
        $callback = function () {};

        $columns = $this->createMock(Columns::class);

        $columns->expects($this->once())
                ->method('sort')
                ->with('price', $callback);

        $builder = new ColumnBuilder($columns, 'price');

        $result = $builder->sort($callback);

        $this->assertSame($builder, $result);
    }

    public function test_builder_fluency_all_methods_chain()
    {
        $columns = $this->createMock(Columns::class);

        $columns->expects($this->once())->method('label')->with('price', 'Price');
        $columns->expects($this->once())->method('position')->with('price', 'after', 'title');
        $columns->expects($this->once())->method('populate');
        $columns->expects($this->once())->method('sort');

        $builder = new ColumnBuilder($columns, 'price');

        $result = $builder
            ->label('Price')
            ->after('title')
            ->populate(function () {})
            ->sort(function () {});

        $this->assertSame($builder, $result);
    }
}
