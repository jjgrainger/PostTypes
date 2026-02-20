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
}
