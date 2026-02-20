<?php

namespace PostTypes\Integrations;

use PostTypes\Contracts\PostTypeContract;

class RegisterPostType
{
    /**
     * PostType to register.
     *
     * @var PostTypeContract
     */
    protected $posttype;

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
     * Add hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('init', [$this, 'registerPostType']);
        add_filter('register_post_type_args', [$this, 'setOptions'], 10, 2);
    }

    /**
     * Register to post type to WordPress.
     *
     * @return void
     */
    public function registerPostType(): void
    {
        if (post_type_exists($this->posttype->name())) {
            return;
        }

        register_post_type($this->posttype->name(), []);
    }

    /**
     * Set the post type options.
     *
     * @param array $options
     * @param string $posttype
     * @return array
     */
    public function setOptions(array $options, string $posttype): array
    {
        if ($posttype !== $this->posttype->name()) {
            return $options;
        }

        $defaults = [
            'public'       => true,
            'show_in_rest' => true,
            'labels'       => $this->posttype->labels(),
            'taxonomies'   => $this->posttype->taxonomies(),
            'supports'     => $this->posttype->supports(),
            'menu_icon'    => $this->posttype->icon(),
            'rewrite'      => [
                'slug' => $this->posttype->slug(),
            ],
        ];

        return array_replace_recursive($options, $defaults, $this->posttype->options());
    }
}
