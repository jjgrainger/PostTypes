<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Registration\Integrations\RegisterTaxonomy;
use PostTypes\Taxonomy;

class RegisterTaxonomyTest extends TestCase
{
    public function test_can_generate_options_with_overrides()
    {
        $stub = $this->getMockBuilder(Taxonomy::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('genre'));

        $stub->expects($this->any())
            ->method('slug')
            ->will($this->returnValue('genre'));


        $stub->expects($this->once())
            ->method('options')
            ->will($this->returnValue([
                'public' => false,
            ]));


        $integration = new RegisterTaxonomy($stub);

        $options = $integration->generateOptions();

        $expected = [
            'public'            => false,
            'show_in_rest'      => true,
            'hierarchical'      => true,
            'show_admin_column' => true,
            'labels'            => [],
            'rewrite'           => [
                'slug' => 'genre',
            ],
        ];

        $this->assertEquals($expected, $options);
    }
}
