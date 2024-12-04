<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Registrars\TaxonomyRegistrar;
use PostTypes\Taxonomy;

class TaxonomyRegistrarTest extends TestCase
{
    /** @test */
    public function canCreateTaxonomyRegistrar()
    {
        $taxonomy = new Taxonomy('genre');
        $registrar = new TaxonomyRegistrar($taxonomy);

        $this->assertInstanceOf(TaxonomyRegistrar::class, $registrar);
    }
}
