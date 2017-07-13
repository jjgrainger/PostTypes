<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Taxonomy;

class TaxonomyTest extends TestCase
{
    public function setUp()
    {
        $this->genres = new Taxonomy('genre');
    }

    /** @test */
    public function canCreateTaxonomy()
    {
        $this->assertInstanceOf(Taxonomy::class, $this->genres);
    }

    /** @test */
    public function hasNameOnInstantiation()
    {
        $this->assertEquals('genre', $this->genres->names['name']);
    }

    /** @test */
    public function hasNamesOnInstantiation()
    {
        $names = [
            'name' => 'genre',
            'singular' => 'Genre',
            'plural' => 'Genres',
            'slug' => 'genres'
        ];

        $genres = new Taxonomy($names);

        $this->assertEquals($genres->names, $names);
    }
}
