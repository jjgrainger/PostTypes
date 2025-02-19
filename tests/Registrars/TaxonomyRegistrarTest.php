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


        $registrar = new TaxonomyRegistrar($stub);

        $options = $registrar->generateOptions();

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

    public function test_can_modify_columns()
    {
        $defaults = [
            'cb' => '',
            'name' => 'Name',
        ];

        $columns = new Columns;
        $columns->add('popularity', 'Popularity', function() {});

        $stub = $this->getMockBuilder(Taxonomy::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('genre'));

        $stub->expects($this->once())
            ->method('columns')
            ->will($this->returnValue($columns));

        $registrar = new TaxonomyRegistrar($stub);
        $output = $registrar->modifyColumns($defaults);

        $expected = [
            'cb' => '',
            'name' => 'Name',
            'popularity' => 'Popularity',
        ];

        $this->assertEquals($expected, $output);
    }

    public function test_can_populate_column()
    {
        $columns = new Columns;

        $stub = $this->createMock(Column::class);

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('column'));

        $stub->expects($this->once())
            ->method('populate')
            ->will($this->returnValue(true));

        $columns->column($stub);

        $stub = $this->getMockBuilder(Taxonomy::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('genre'));

        $stub->expects($this->once())
            ->method('columns')
            ->will($this->returnValue($columns));

        $registrar = new TaxonomyRegistrar($stub);
        $registrar->populateColumns('', 'column', 1);
    }

    public function test_can_set_sortable_columns()
    {
        $columns = new Columns;
        $columns->sortable('column', function() {});

        $sortable = [
            'title' => 'title',
        ];

        $stub = $this->getMockBuilder(Taxonomy::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('genre'));

        $stub->expects($this->once())
            ->method('columns')
            ->will($this->returnValue($columns));

        $registrar = new TaxonomyRegistrar($stub);
        $output = $registrar->setSortableColumns($sortable);

        $expected = [
            'title' => 'title',
            'column' => 'column',
        ];

        $this->assertEquals($expected, $output);
    }
}
