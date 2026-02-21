<?php

namespace PostTypes;

use PostTypes\Columns;
use PostTypes\Contracts\TaxonomyContract;
use PostTypes\Registration\Service;

abstract class Taxonomy implements TaxonomyContract
{
    /**
     * Taxonomy name.
     *
     * @return string
     */
    abstract public function name(): string;

    /**
     * Taxonomy slug.
     *
     * @return string
     */
    public function slug(): string
    {
        return $this->name();
    }

    /**
     * Taxonomy labels.
     *
     * @return array
     */
    public function labels(): array
    {
        return [];
    }

    /**
     * Taxonomy options.
     *
     * @return array
     */
    public function options(): array
    {
        return [];
    }

    /**
     * Taxonomy post types.
     *
     * @return array
     */
    public function posttypes(): array
    {
        return [];
    }

    /**
     * Taxonomy columns.
     *
     * @param Columns $columns
     * @return Columns
     */
    public function columns(Columns $columns): Columns
    {
        return $columns;
    }

    /**
     * Taxonomy hooks.
     *
     * @return void
     */
    public function hooks(): void
    {
        return;
    }

    /**
     * Register the Taxonomy.
     *
     * @return void
     */
    public function register(): void
    {
        Service::forTaxonomy()->register($this);
    }
}
