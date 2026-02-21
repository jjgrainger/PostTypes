<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Columns;
use PostTypes\PostType;

class PostTypeTest extends TestCase
{
    public function test_post_type_returns_defaults()
    {
        $stub = $this->getMockForAbstractClass(PostType::class);

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('book'));

        $columns = new Columns;

        $this->assertEquals('book', $stub->slug());
        $this->assertEquals([], $stub->labels());
        $this->assertEquals([], $stub->options());
        $this->assertEquals([], $stub->taxonomies());
        $this->assertEquals(['title', 'editor'], $stub->supports());
        $this->assertEquals(null, $stub->icon());
        $this->assertEquals([], $stub->filters());
        $this->assertEquals($columns, $stub->columns($columns));
        $this->assertEquals(null, $stub->hooks());
    }
}
