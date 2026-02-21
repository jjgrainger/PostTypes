<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Columns;
use PostTypes\Taxonomy;

class TaxonomyTest extends TestCase
{
    public function test_taxonomy_returns_defaults()
    {
        $stub = $this->getMockForAbstractClass(Taxonomy::class);

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('genre'));

        $columns = new Columns;

        $this->assertEquals('genre', $stub->slug());
        $this->assertEquals([], $stub->labels());
        $this->assertEquals([], $stub->options());
        $this->assertEquals([], $stub->posttypes());
        $this->assertEquals($columns, $stub->columns($columns));
        $this->assertEquals(null, $stub->hooks());
    }
}
