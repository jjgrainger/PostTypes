<?php

namespace PostTypes\Contracts;

use PostTypes\Columns;

interface TaxonomyContract
{
    /**
     * Taxonomy name.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Taxonomy slug.
     *
     * @return string
     */
    public function slug(): string;

    /**
     * Taxonomy labels.
     *
     * @return array
     */
    public function labels(): array;

    /**
     * Taxonomy options.
     *
     * @return array
     */
    public function options(): array;

    /**
     * Taxonomy post types.
     *
     * @return array
     */
    public function posttypes(): array;

    /**
     * Taxonomy columns.
     *
     * @param Columns $columns
     * @return Columns
     */
    public function columns(Columns $columns): Columns;

    /**
     * Taxonomy hooks.
     *
     * @return void
     */
    public function hooks(): void;
}
