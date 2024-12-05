<?php

use PHPUnit\Framework\TestCase;
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
}
