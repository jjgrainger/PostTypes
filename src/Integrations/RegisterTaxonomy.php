<?php

namespace PostTypes\Integrations;

use PostTypes\Contracts\TaxonomyContract;

class RegisterTaxonomy
{
    /**
     * Taxonomy to register.
     *
     * @var TaxonomyContract
     */
    private $taxonomy;

    /**
     * Constructor.
     *
     * @param TaxonomyContract $taxonomy
     */
    public function __construct(TaxonomyContract $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * Register the Taxonomy to WordPress.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('init', [$this, 'registerTaxonomy'], 9);
        add_action('init', [$this, 'registerTaxonomyToPostTypes'], 10);

        $this->taxonomy->hooks();
    }

    /**
     * Register the Taxonomy.
     *
     * @return void
     */
    public function registerTaxonomy(): void
    {
        register_taxonomy($this->taxonomy->name(), [], $this->generateOptions());
    }

    /**
     * Generate Taxonomy options.
     *
     * @return array
     */
    public function generateOptions(): array
    {
        $defaults = [
            'public'            => true,
            'show_in_rest'      => true,
            'hierarchical'      => true,
            'show_admin_column' => true,
            'labels'            => $this->taxonomy->labels(),
            'rewrite'           => [
                'slug' => $this->taxonomy->slug(),
            ],
        ];

        return array_replace_recursive($defaults, $this->taxonomy->options());
    }

    /**
     * Register Taxonomy to post types.
     *
     * @return void
     */
    public function registerTaxonomyToPostTypes(): void
    {
        foreach ($this->taxonomy->posttypes() as $posttype) {
            register_taxonomy_for_object_type($this->taxonomy->name(), $posttype);
        }
    }
}
