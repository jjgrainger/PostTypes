<?php

use PHPUnit\Framework\TestCase;
use PostTypes\ColumnBuilder;
use PostTypes\Columns;

class ColumnBuilderTest extends TestCase
{
    public function test_label_sets_column_label()
    {
        $manager = $this->createMock(Columns::class);

        $manager->expects($this->once())
                ->method('add')
                ->with('price', 'Price Label');

        $builder = new ColumnBuilder($manager, 'price');

        $result = $builder->label('Price Label');

        $this->assertSame($builder, $result); // fluent
    }

    public function test_position_sets_position_correctly()
    {
        $manager = $this->createMock(Columns::class);

        $manager->expects($this->once())
                ->method('position')
                ->with('price', 'after', 'title');

        $builder = new ColumnBuilder($manager, 'price');

        $result = $builder->position('after', 'title');

        $this->assertSame($builder, $result);
    }

    public function test_after_sets_position_after_reference()
    {
        $manager = $this->createMock(Columns::class);

        $manager->expects($this->once())
                ->method('position')
                ->with('price', 'after', 'title');

        $builder = new ColumnBuilder($manager, 'price');

        $result = $builder->after('title');

        $this->assertSame($builder, $result);
    }

    public function test_before_sets_position_before_reference()
    {
        $manager = $this->createMock(Columns::class);

        $manager->expects($this->once())
                ->method('position')
                ->with('price', 'before', 'title');

        $builder = new ColumnBuilder($manager, 'price');

        $result = $builder->before('title');

        $this->assertSame($builder, $result);
    }

    public function test_populate_sets_populate_callback()
    {
        $callback = function () {};

        $manager = $this->createMock(Columns::class);

        $manager->expects($this->once())
                ->method('populate')
                ->with('price', $callback);

        $builder = new ColumnBuilder($manager, 'price');

        $result = $builder->populate($callback);

        $this->assertSame($builder, $result);
    }

    public function test_sort_sets_sort_callback()
    {
        $callback = function () {};

        $manager = $this->createMock(Columns::class);

        $manager->expects($this->once())
                ->method('sort')
                ->with('price', $callback);

        $builder = new ColumnBuilder($manager, 'price');

        $result = $builder->sort($callback);

        $this->assertSame($builder, $result);
    }

    public function test_builder_fluency_all_methods_chain()
    {
        $manager = $this->createMock(Columns::class);

        $manager->expects($this->once())->method('add')->with('price', 'Price');
        $manager->expects($this->once())->method('position')->with('price', 'after', 'title');
        $manager->expects($this->once())->method('populate');
        $manager->expects($this->once())->method('sort');

        $builder = new ColumnBuilder($manager, 'price');

        $result = $builder
            ->label('Price')
            ->after('title')
            ->populate(function () {})
            ->sort(function () {});

        $this->assertSame($builder, $result);
    }
}
