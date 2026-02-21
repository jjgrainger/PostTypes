<?php

namespace PostTypes\Registrars;

use PostTypes\Contracts\TaxonomyContract;
use PostTypes\Integrations\ManageTaxonomyColumns;
use PostTypes\Integrations\RegisterTaxonomy;

class TaxonomyRegistrar
{
    /**
     * Taxonomy to register.
     *
     * @var TaxonomyContract
     */
    private $taxonomy;

    /**
     * Integrations.
     *
     * @var array
     */
    private $integrations = [
        RegisterTaxonomy::class,
        ManageTaxonomyColumns::class,
    ];

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
        foreach ($this->integrations as $integration) {
            (new $integration($this->taxonomy))->register();
        }
    }
}
