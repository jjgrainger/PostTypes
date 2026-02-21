<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Column;
use PostTypes\Columns;
use PostTypes\Registrars\TaxonomyRegistrar;
use PostTypes\Taxonomy;

class TaxonomyRegistrarTest extends TestCase
{
    public function test_can_create_registrar()
    {
        $stub = $this->getMockForAbstractClass(Taxonomy::class);

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('genre'));

        $registrar = new TaxonomyRegistrar($stub);

        $this->assertInstanceOf(TaxonomyRegistrar::class, $registrar);
    }
}
