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

    /** @test */
    public function usesSetColumnsOverDefaults()
    {
        $defaults = [
            'title' => 'Title',
            'author' => 'Author',
            'comments' => 'Comments',
            'date' => 'Date'
        ];

        $columns = [
            'title' => 'Title',
            'author' => 'Author',
            'date' => 'Date'
        ];

        $this->columns->set($columns);

        $output = $this->columns->modifyColumns($defaults);

        $this->assertEquals($output, $columns);
    }

    /** @test */
    public function addsColumnsToDefaults()
    {
        $columns = [
            'title' => 'Title',
            'author' => 'Author',
            'comments' => 'Comments',
            'date' => 'Date'
        ];

        $this->columns->add(['genre' => 'Genres']);

        $output = $this->columns->modifyColumns($columns);

        $columns['genre'] = 'Genres';

        $this->assertEquals($output, $columns);
    }

    /** @test */
    public function hideColumnsFromDefaults()
    {
        $columns = [
            'title' => 'Title',
            'author' => 'Author',
            'comments' => 'Comments',
            'date' => 'Date'
        ];

        $this->columns->hide('comments');

        $output = $this->columns->modifyColumns($columns);

        unset($columns['comments']);

        $this->assertEquals($output, $columns);
    }

    /** @test */
    public function setOrderOfDefaultColumns()
    {
        $columns = [
            'title' => 'Title',
            'author' => 'Author',
            'comments' => 'Comments',
            'date' => 'Date'
        ];

        $this->columns->order([
            'date' => 1,
            'title' => 3
        ]);

        $output = $this->columns->modifyColumns($columns);

        $expected = [
            'date' => 'Date',
            'author' => 'Author',
            'title' => 'Title',
            'comments' => 'Comments',
        ];

        $this->assertEquals($output, $expected);
    }

    /** @test */
    public function canModifyColumns()
    {
        $defaults = [
            'title' => 'Title',
            'author' => 'Author',
            'comments' => 'Comments',
            'date' => 'Date'
        ];

        $expected = [
            'title' => 'Title',
            'genre' => 'Genre',
            'author' => 'Author',
            'date' => 'Date'
        ];

        $this->columns->hide('comments');

        $this->columns->add(['genre' => 'Genre']);

        $this->columns->order([
            'genre' => 2,
        ]);

        $output = $this->columns->modifyColumns($defaults);

        $this->assertEquals($output, $expected);
    }
}
