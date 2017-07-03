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
}
