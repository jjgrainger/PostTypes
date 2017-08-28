<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Columns;

class ColumnsTest extends TestCase
{
    public function setUp()
    {
        $this->columns = new Columns;
    }

    /** @test */
    public function canCreateColumns()
    {
        $this->assertInstanceOf(Columns::class, $this->columns);
    }

    /** @test */
    public function canSetColumns()
    {
        $columns = [
            'title' => 'Title',
            'date' => 'Date',
        ];

        $this->columns->set($columns);

        $this->assertEquals($this->columns->items, $columns);
    }

    /** @test */
    public function canAddColumns()
    {
        $columns = [
            'genre' => 'Genre'
        ];

        $this->columns->add($columns);

        $this->assertEquals($this->columns->add, $columns);
    }

    /** @test */
    public function canHideColumns()
    {
        $columns = [
            'date'
        ];

        $this->columns->hide($columns);

        $this->assertEquals($this->columns->hide, $columns);
    }

    /** @test */
    public function canPopulateColumns()
    {
        $callable = function($column, $post_id) {
            echo $post_id;
        };

        $this->columns->populate('post_id', $callable);

        $this->assertEquals($this->columns->populate['post_id'], $callable);
    }

    /** @test */
    public function canOrderColumns()
    {
        $columns = [
            'date' => 3,
            'genre' => 2
        ];

        $this->columns->order($columns);

        $this->assertEquals($this->columns->positions, $columns);
    }

    /** @test */
    public function canSortColumns()
    {
        $columns = [
            'rating' => ['_rating', true]
        ];

        $this->columns->sortable($columns);

        $this->assertEquals($this->columns->sortable, $columns);
    }
}
