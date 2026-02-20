<?php

namespace PostTypes\Registrars;

use PostTypes\Contracts\PostTypeContract;
use PostTypes\Integrations\ManagePostTypeColumns;
use PostTypes\Integrations\ManagePostTypeFilters;
use PostTypes\Integrations\RegisterPostType;

class PostTypeRegistrar
{
    /**
     * PostType to register.
     *
     * @var PostTypeContract
     */
    private $posttype;

    /**
     * Integrations.
     *
     * @var array
     */
    private $integrations = [
        RegisterPostType::class,
        ManagePostTypeFilters::class,
        ManagePostTypeColumns::class,
    ];

    /**
     * Constructor.
     *
     * @param PostTypeContract $posttype
     */
    public function __construct(PostTypeContract $posttype)
    {
        $this->posttype = $posttype;
    }

    /**
     * Register the PostType to WordPress.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->integrations as $integration) {
            (new $integration($this->posttype))->register();
        }
    }
}
