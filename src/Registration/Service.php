<?php

namespace PostTypes\Registration;

use PostTypes\Contracts\PostTypeContract;
use PostTypes\Contracts\TaxonomyContract;
use PostTypes\Registration\Integrations\ManagePostTypeColumns;
use PostTypes\Registration\Integrations\ManagePostTypeFilters;
use PostTypes\Registration\Integrations\ManageTaxonomyColumns;
use PostTypes\Registration\Integrations\RegisterPostType;
use PostTypes\Registration\Integrations\RegisterTaxonomy;

class Service
{
    /**
     * Integrations.
     *
     * @var array
     */
    private array $integrations;

    /**
     * Constructor
     *
     * @param array $integrations
     */
    public function __construct(array $integrations = [])
    {
        $this->integrations = $integrations;
    }

    /**
     * Register a PostType or Taxonomy.
     *
     * @param PostTypeContract|TaxonomyContract $definition
     * @return void
     */
    public function register(PostTypeContract|TaxonomyContract $definition): void
    {
        foreach ($this->integrations as $integration) {
            (new $integration($definition))->register();
        }
    }

    /**
     * Create a Registration Service for PostType.
     *
     * @return self
     */
    public static function forPostType(): self
    {
        return new self([
            RegisterPostType::class,
            ManagePostTypeFilters::class,
            ManagePostTypeColumns::class,
        ]);
    }

    /**
     * Create a Registration Service for Taxonomy.
     *
     * @return self
     */
    public static function forTaxonomy(): self
    {
        return new self([
            RegisterTaxonomy::class,
            ManageTaxonomyColumns::class,
        ]);
    }
}
