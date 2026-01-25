<?php

namespace PostTypes\Contracts;

use PostTypes\Columns;

interface PostTypeContract
{
    /**
     * Post type name.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Post type slug.
     *
     * @return string
     */
    public function slug(): string;

    /**
     * Post types labels.
     *
     * @return array
     */
    public function labels(): array;

    /**
     * Post types options.
     *
     * @return array
     */
    public function options(): array;

    /**
     * Post type icon.
     *
     * @return string|null
     */
    public function icon(): ?string;

    /**
     * Post type taxonomies.
     *
     * @return array
     */
    public function taxonomies(): array;

    /**
     * Post type supports array.
     *
     * @return array
     */
    public function supports(): array;

    /**
     * Post type filters.
     *
     * @return array
     */
    public function filters(): array;

    /**
     * Post type columns.
     *
     * @param Columns $column
     * @return Columns
     */
    public function columns(Columns $column): Columns;

    /**
     * Post type hooks.
     *
     * @return void
     */
    public function hooks(): void;
}
