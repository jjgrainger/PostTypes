<?php

use PHPUnit\Framework\TestCase;
use PostTypes\PostType;

class PostTypeTest extends TestCase
{
    /** @test */
    public function canCreatePostType()
    {
        $books = new PostType('book');

        $this->assertInstanceOf(PostType::class, $books);
    }

    /** @test */
    public function hasNameOnInstantiation()
    {
        $books = new PostType('book');

        $this->assertEquals($books->names['name'], 'book');
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
        $books = new PostType('books');

        $this->assertEquals($books->options, []);
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
}
