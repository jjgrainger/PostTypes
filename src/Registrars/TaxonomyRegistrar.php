<?php

namespace PostTypes\Registrars;

use PostTypes\Taxonomy;

class TaxonomyRegistrar
{
    protected $taxonomy;

    public function __construct(Taxonomy $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    public function register()
    {
        // Get the Taxonomy name.
        $name = $this->taxonomy->name;

        // Register the taxonomy, set priority to 9 so taxonomies are registered before PostTypes
        add_action('init', [$this, 'registerTaxonomy'], 9);

        // Assign taxonomy to post type objects
        add_action('init', [$this, 'registerTaxonomyToObjects'], 10);

        if (isset($this->taxonomy->columns)) {
            // Modify the columns for the Taxonomy
            add_filter("manage_edit-' . $name . '_columns", [$this, 'modifyColumns']);

            // populate the columns for the Taxonomy
            add_filter('manage_' . $name . '_custom_column', [$this, 'populateColumns'], 10, 3);

            // set custom sortable columns
            add_filter('manage_edit-' . $name . '_sortable_columns', [$this, 'setSortableColumns']);

            // run action that sorts columns on request
            add_action('parse_term_query', [$this, 'sortSortableColumns']);
        }
    }

    /**
     * Register the Taxonomy to WordPress
     * @return void
     */
    public function registerTaxonomy()
    {
        // Get the existing taxonomy options if it exists.
        $options = (taxonomy_exists($this->taxonomy->name)) ? (array) get_taxonomy($this->taxonomy->name) : [];

        // create options for the Taxonomy.
        $options = array_replace_recursive($options, $this->taxonomy->createOptions());

        // register the Taxonomy with WordPress.
        register_taxonomy($this->taxonomy->name, null, $options);
    }

    /**
     * Register the Taxonomy to PostTypes
     * @return void
     */
    public function registerTaxonomyToObjects()
    {
        // register Taxonomy to each of the PostTypes assigned
        if (empty($this->taxonomy->posttypes)) {
            return;
        }

        foreach ($this->taxonomy->posttypes as $posttype) {
            register_taxonomy_for_object_type($this->taxonomy->name, $posttype);
        }
    }

    /**
     * Modify the columns for the Taxonomy
     * @param  array  $columns  The WordPress default columns
     * @return array
     */
    public function modifyColumns($columns)
    {
        return $this->taxonomy->columns->modifyColumns($columns);
    }

    /**
     * Populate custom columns for the Taxonomy
     * @param  string $content
     * @param  string $column
     * @param  int    $term_id
     */
    public function populateColumns($content, $column, $term_id)
    {
        if (isset($this->taxonomy->columns->populate[$column])) {
            $content = call_user_func_array(
                $this->taxonomy->columns()->populate[$column],
                [$content, $column, $term_id]
            );
        }

        return $content;
    }

    /**
     * Make custom columns sortable
     * @param array $columns Default WordPress sortable columns
     */
    public function setSortableColumns($columns)
    {
        if (!empty($this->taxonomy->columns()->sortable)) {
            $columns = array_merge($columns, $this->taxonomy->columns()->sortable);
        }

        return $columns;
    }

    /**
     * Set query to sort custom columns
     * @param WP_Term_Query $query
     */
    public function sortSortableColumns($query)
    {
        // don't modify the query if we're not in the post type admin
        if (!is_admin() || !in_array($this->taxonomy->name, $query->query_vars['taxonomy'] ?? [])) {
            return;
        }

        // check the orderby is a custom ordering
        if (isset($_GET['orderby']) && array_key_exists($_GET['orderby'], $this->taxonomy->columns()->sortable)) {
            // get the custom sorting options
            $meta = $this->taxonomy->columns()->sortable[$_GET['orderby']];

            // check ordering is not numeric
            if (is_string($meta)) {
                $meta_key = $meta;
                $orderby = 'meta_value';
            } else {
                $meta_key = $meta[0];
                $orderby = 'meta_value_num';
            }

            // set the sort order
            $query->query_vars['orderby'] = $orderby;
            $query->query_vars['meta_key'] = $meta_key;
        }
    }
}
