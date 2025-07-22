<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Column;
use PostTypes\Columns;
use PostTypes\PostType;
use PostTypes\Registrars\PostTypeRegistrar;

class PostTypeRegistrarTest extends TestCase
{
    public function test_can_create_registrar()
    {
        $stub = $this->getMockForAbstractClass(PostType::class);

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('book'));

        $registrar = new PostTypeRegistrar($stub);

        $this->assertInstanceOf(PostTypeRegistrar::class, $registrar);
    }

    public function test_will_modify_post_type()
    {
        $stub = $this->getMockForAbstractClass(PostType::class);

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('book'));

        $registrar = new PostTypeRegistrar($stub);

        $args = [
            'public' => false,
        ];

        $options = $registrar->modifyPostType($args, 'book');

        $expected = [
            'public'       => true,
            'show_in_rest' => true,
            'labels'       => [],
            'taxonomies'   => [],
            'supports'     => ['title', 'editor'],
            'menu_icon'    => null,
            'rewrite'      => [
                'slug' => 'book',
            ],
        ];

        $this->assertEquals($expected, $options);
    }

    public function test_will_not_modify_post_type_if_name_does_not_match()
    {
        $stub = $this->getMockForAbstractClass(PostType::class);

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('book'));

        $registrar = new PostTypeRegistrar($stub);

        $args = [
            'public' => false,
        ];

        $options = $registrar->modifyPostType($args, 'post');

        $this->assertEquals($args, $options);
    }

    public function test_can_modify_columns()
    {
        $defaults = [
            'cb' => '',
            'title' => 'Title',
            'author' => 'Author',
        ];

        $columns = new Columns;
        $columns->add('date', 'Date', function() {});

        $stub = $this->getMockBuilder(PostType::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('book'));

        $stub->expects($this->once())
            ->method('columns')
            ->will($this->returnValue($columns));

        $registrar = new PostTypeRegistrar($stub);
        $registrar->createColumns();
        $output = $registrar->modifyColumns($defaults);

        $expected = [
            'cb' => '',
            'title' => 'Title',
            'author' => 'Author',
            'date' => 'Date',
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

        $stub = $this->getMockBuilder(PostType::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('book'));

        $stub->expects($this->once())
            ->method('columns')
            ->will($this->returnValue($columns));

        $registrar = new PostTypeRegistrar($stub);
        $registrar->createColumns();
        $registrar->populateColumns('column', 1);
    }

    public function test_can_set_sortable_columns()
    {
        $columns = new Columns;
        $columns->sortable('column', function() {});

        $sortable = [
            'title' => 'title',
        ];

        $stub = $this->getMockBuilder(PostType::class)
            ->getMock();

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('book'));

        $stub->expects($this->once())
            ->method('columns')
            ->will($this->returnValue($columns));

        $registrar = new PostTypeRegistrar($stub);
        $registrar->createColumns();
        $output = $registrar->setSortableColumns($sortable);

        $expected = [
            'title' => 'title',
            'column' => 'column',
        ];

        $this->assertEquals($expected, $output);
    }
}
