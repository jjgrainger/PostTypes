<?php

use PHPUnit\Framework\TestCase;
use PostTypes\PostType;

class PostTypeTest extends TestCase
{
    public function setUp() {
        // setup basic PostType
        $this->books = new PostType('book');
    }

    /** @test */
    public function canCreatePostType()
    {
        $this->assertInstanceOf(PostType::class, $this->books);
    }

    /** @test */
    public function hasNameOnInstantiation()
    {
        $this->assertEquals($this->books->names['name'], 'book');
    }

    /** @test */
    public function hasNamesOnInstantiation()
    {
        $names = [
            'name' => 'book',
            'singular' => 'Book',
            'plural' => 'Books',
            'slug' => 'books'
        ];

        $books = new PostType($names);

        $this->assertEquals($books->names, $names);
    }

    /** @test */
    public function hasOptionsOnInstantiation()
    {
        $this->assertEquals($this->books->options, []);
    }

    /** @test */
    public function hasCustomOptionsOnInstantiation()
    {
        $options = [
            'public' => true
        ];

        $books = new PostType('books', $options);

        $this->assertEquals($books->options, $options);
    }

    /** @test */
    public function hasLabelsOnInstantiation()
    {
        $this->assertEquals($this->books->labels, []);
    }

    public function hasCustomLabelsOnInstantiation()
    {
        $labels = [
            'name' => 'Books',
            'add_new' => 'Add New Book'
        ];

        $books = new PostType('books', [], $labels);

        $this->assertEquals($books->labels, $labels);
    }

    /** @test */
    public function taxonomiesEmptyOnInstantiation()
    {
        $this->assertEquals($this->books->taxonomies, []);
    }

    /** @test */
    public function hasCustomTaxonomiesWhenPassed()
    {
        $books = $this->books;

        $books->taxonomy('genre');

        $this->assertEquals($books->taxonomies, ['genre']);
    }

    /** @test */
    public function filtersNullOnInstantiation()
    {
        $this->assertNull($this->books->filters);
    }

    /** @test */
    public function hasFiltersWhenAdded()
    {
        $books = $this->books;

        $books->filters(['genre']);

        $this->assertEquals($books->filters, ['genre']);
    }
}
