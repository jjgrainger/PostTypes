<?php

namespace PostTypes\Contracts;

interface Taxonomy
{
    /**
     * Set the Taxonomy names.
     *
     * @param array $names An array of taxonomy names.
     *
     * @return PostTypes\Taxonomy
     */
    public function names(array $names);

    /**
     * Set the Taxonomy options.
     *
     * @param array $options An array of taxonomy options.
     *
     * @return PostTypes\Taxonomy
     */
    public function options(array $options);

    /**
     * Set the Taxonomy labels.
     *
     * @param array $labels An array of taxonomy labels.
     *
     * @return PostTypes\Taxonomy
     */
    public function labels(array $labels);

    /**
     * Set the post types to register to the taxonomy.
     *
     * @param array $posttypes An array of post types to register to the taxonomy.
     *
     * @return PostTypes\Taxonomy
     */
    public function posttype(array $posttypes);

    /**
     * Returns the Column Manager.
     *
     * @return PostTypes\Columns
     */
    public function columns();

    /**
     * Registers the Taxonomy to WordPress.
     *
     * @return void
     */
    public function register();
}
