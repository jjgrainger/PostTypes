<?php

namespace PostTypes;

use PostTypes\Columns;
use PostTypes\Contracts\PostTypeContract;
use PostTypes\Registrars\PostTypeRegistrar;

abstract class PostType implements PostTypeContract
{
    /**
     * Post type name.
     *
     * @return string
     */
    abstract public function name(): string;

    /**
     * Post type slug.
     *
     * @return string
     */
    public function slug(): string
    {
        return $this->name();
    }

    /**
     * Post type labels.
     *
     * @return array
     */
    public function labels(): array
    {
        return [];
    }

    /**
     * Post type options.
     *
     * @return array
     */
    public function options(): array
    {
        return [];
    }

    /**
     * Post type taxonomies.
     *
     * @return array
     */
    public function taxonomies(): array
    {
        return [];
    }

    /**
     * Post type supports.
     *
     * @return array
     */
    public function supports(): array
    {
        return [
            'title',
            'editor',
        ];
    }

    /**
     * Post type icon.
     *
     * @return string|null
     */
    public function icon(): ?string
    {
        return null;
    }

    /**
     * Post type filters.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * Post type columns.
     *
     * @param Columns $columns
     * @return Columns
     */
    public function columns(Columns $columns): Columns
    {
        return $columns;
    }

    /**
     * Post type additional hooks.
     *
     * @return void
     */
    public function hooks(): void
    {
        return;
    }

    /**
     * Register the post type.
     *
     * @return void
     */
    public function register(): void
    {
        (new PostTypeRegistrar($this))->register();
    }
}
