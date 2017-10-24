<?php

use PHPUnit\Framework\TestCase;
use PostTypes\PostType;
use PostTypes\Columns;

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

    /** @test */
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

    /** @test */
    public function iconNullOnInstantiation()
    {
        $this->assertNull($this->books->icon);
    }

    /** @test */
    public function hasIconWhenSet()
    {
        $books = $this->books;

        $books->icon('dashicon-book-alt');

        $this->assertEquals($books->icon, 'dashicon-book-alt');
    }

    /** @test */
    public function columnsIsNullOnInstantiation()
    {
        $this->assertEquals($this->books->columns, null);
    }

    /** @test */
    public function columnsReturnsInstanceOfColumns()
    {
        $this->assertInstanceOf(Columns::class, $this->books->columns());
    }

    /** @test */
    public function namesCreatedFromName()
    {
        $this->books->createNames();

        $this->assertEquals($this->books->name, 'book');
        $this->assertEquals($this->books->singular, 'Book');
        $this->assertEquals($this->books->plural, 'Books');
        $this->assertEquals($this->books->slug, 'books');
    }

    /** @test */
    public function passedNamesAreUsed()
    {
        $names = [
            'name' => 'book',
            'singular' => 'Single Book',
            'plural' => 'Multiple Books',
            'slug' => 'slug_books',
        ];

        $this->books->names($names);

        $this->books->createNames();

        $this->assertEquals($this->books->name, 'book');
        $this->assertEquals($this->books->singular, 'Single Book');
        $this->assertEquals($this->books->plural, 'Multiple Books');
        $this->assertEquals($this->books->slug, 'slug_books');
    }

    /** @test */
    public function defaultOptionsUsedIfNotSet()
    {
        // generated options
        $options = $this->books->createOptions();

        // expected options
        $defaults = [
            'public' => true,
            'labels' => $this->books->createLabels(),
            'rewrite' => [
                'slug' => $this->books->slug
            ]
        ];

        $this->assertEquals($options, $defaults);
    }

    /** @test */
    public function defaultLabelsAreGenerated()
    {
        $labels = $this->books->createLabels();

        $defaults = [
            'name' => $this->books->plural,
            'singular_name' => $this->books->singular,
            'menu_name' => $this->books->plural,
            'all_items' => $this->books->plural,
            'add_new' => "Add New",
            'add_new_item' => "Add New {$this->books->singular}",
            'edit_item' => "Edit {$this->books->singular}",
            'new_item' => "New {$this->books->singular}",
            'view_item' => "View {$this->books->singular}",
            'search_items' => "Search {$this->books->plural}",
            'not_found' => "No {$this->books->plural} found",
            'not_found_in_trash' => "No {$this->books->plural} found in Trash",
            'parent_item_colon' => "Parent {$this->books->singular}:",
        ];

        $this->assertEquals($labels, $defaults);
    }

    /** @test */
    public function filtersAreEmptyIfNotSetAndNoTaxonomies()
    {
        $filters = $this->books->getFilters();

        $this->assertEquals($filters, []);
    }

    /** @test */
    public function filtersAreSameAsTaxonomyIfNotSet()
    {
        $this->books->taxonomy('genre');

        $filters = $this->books->getFilters();

        $this->assertEquals($filters, ['genre']);
    }

    /** @test */
    public function filtersAreWhatAssignedIfPassed()
    {
        $this->books->filters(['genre', 'published']);

        $this->books->taxonomy('genre');

        $filters = $this->books->getFilters();

        $this->assertEquals($filters, ['genre', 'published']);
    }

    /** @test */
    public function filtersAreEmptyIfSetWithEmptyArray()
    {
        $this->books->filters([]);

        $this->books->taxonomy('genre');

        $filters = $this->books->getFilters();

        $this->assertEquals($filters, []);
    }
}
