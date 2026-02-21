<?php

use PHPUnit\Framework\TestCase;
use PostTypes\Registration\Integrations\RegisterPostType;
use PostTypes\PostType;

class RegisterPostTypeTest extends TestCase
{
    public function test_sets_correct_options_for_post_type()
    {
        $stub = $this->getMockForAbstractClass(PostType::class);

        $stub->expects($this->any())
            ->method('name')
            ->will($this->returnValue('book'));

        $inegration = new RegisterPostType($stub);

        $args = [
            'public' => false,
        ];

        $options = $inegration->setOptions($args, 'book');

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

        $inegration = new RegisterPostType($stub);

        $args = [
            'public' => false,
        ];

        $options = $inegration->setOptions($args, 'post');

        $this->assertEquals($args, $options);
    }
}
