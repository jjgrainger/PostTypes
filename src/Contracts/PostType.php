<?php

namespace PostTypes\Contracts;

interface PostType
{
    /**
     * Set the Post Type names.
     *
     * @param array $names An array of post type names.
     *
     * @return PostTypes\PostType
     */
    public function names(array $names);

    /**
     * Set the Post Type options.
     *
     * @param array $options An array of post type options.
     *
     * @return PostTypes\PostType
     */
    public function options(array $options);

    /**
     * Set the Post Type labels.
     *
     * @param array $labels An array of post type labels.
     *
     * @return PostTypes\PostType
     */
    public function labels(array $labels);

    /**
     * Set the Post Type Taxonomies.
     *
     * @param array $taxonomies An array of taxonomies registered to the post type.
     *
     * @return PostType\PostType
     */
    public function taxonomy(array $taxonomies);

    /**
     * Set the admin filters for the Post Type.
     *
     * @param array $filters An array of Taxonomy names to use as filters.
     *
     * @return PostType\PostType
     */
    public function filters(array $filters);

    /**
     * Set the Post Type icon.
     *
     * @param string $icon The dashicon icon name.
     *
     * @return PostType\PostType
     */
    public function icon(string $icon);

    /**
     * Returns the Column Manager.
     *
     * @return PostTypes\Columns
     */
    public function columns();

    /**
     * Registers the Post Type to WordPress.
     *
     * @return void
     */
    public function register();
}
