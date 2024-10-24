<?php

use PHPUnit\Framework\TestCase;
use PostTypes\PostType;
use PostTypes\Registrars\PostTypeRegistrar;

class PostTypeRegistrarTest extends TestCase
{
    /** @test */
    public function canModifyPostType()
    {
        $posttype = new PostType('post', [
            'public' => false,
        ]);

        $registrar = new PostTypeRegistrar($posttype);

        $options = $registrar->modifyPostType([
            'public' => true,
        ], 'post');

        $this->assertEquals(false, $options['public']);
    }
}
