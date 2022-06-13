<?php

namespace PostTypes\Registrars;

use PostTypes\Taxonomy;

class TaxonomyRegistrar
{
    /**
     * Constructor.
     *
     * @param Taxonomy $taxonomy The Taxonomy class to register to WordPress.
     *
     * @return void
     */
    public function __construct(Taxonomy $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * Register the Taxonomy to WordPress.
     *
     * @return void
     */
    public function register()
    {
        // Create the required names to register the Taxonomy.
        $this->createNames();

        // Register the taxonomy, set priority to 9 to register before PostTypes.
        add_action('init', [$this, 'registerTaxonomy'], 9);

        // Assign taxonomy to post type objects.
        add_action('init', [$this, 'registerTaxonomyToObjects']);

        if (isset($this->taxonomy->columns)) {
            // Modify the columns for the Taxonomy.
            add_filter("manage_edit-{$this->taxonomy->name}_columns", [$this, 'modifyColumns']);

            // Populate the columns for the Taxonomy.
            add_filter("manage_{$this->taxonomy->name}_custom_column", [$this, 'populateColumns'], 10, 3);

            // Set custom sortable columns.
            add_filter("manage_edit-{$this->taxonomy->name}_sortable_columns", [$this, 'setSortableColumns']);

            // Run action that sorts columns on request.
            add_action('parse_term_query', [$this, 'sortSortableColumns']);
        }
    }

    /**
     * Register the Taxonomy to WordPress.
     *
     * @return void
     */
    public function registerTaxonomy()
    {
        // Get the existing taxonomy options if it exists.
        $options = (taxonomy_exists($this->taxonomy->name)) ? (array) get_taxonomy($this->taxonomy->name) : [];

        // Create options for the Taxonomy.
        $options = array_replace_recursive($options, $this->createOptions());

        // Register the Taxonomy with WordPress.
        register_taxonomy($this->taxonomy->name, null, $options);
    }

    /**
     * Register the Taxonomy to PostTypes.
     *
     * @return void
     */
    public function registerTaxonomyToObjects()
    {
        // Register Taxonomy to each of the PostTypes assigned.
        if (!empty($this->taxonomy->posttypes)) {
            foreach ($this->taxonomy->posttypes as $posttype) {
                register_taxonomy_for_object_type($this->taxonomy->name, $posttype);
            }
        }
    }

    /**
     * Create names for the Taxonomy.
     *
     * @return void
     */
    public function createNames()
    {
        $required = [
            'name',
            'singular',
            'plural',
            'slug',
        ];

        foreach ($required as $key) {
            // if the name is set, assign it
            if (isset($this->taxonomy->names[$key])) {
                $this->taxonomy->$key = $this->taxonomy->names[$key];
                continue;
            }

            // if the key is not set and is singular or plural
            if (in_array($key, ['singular', 'plural'])) {
                // create a human friendly name
                $name = ucwords(strtolower(str_replace(['-', '_'], ' ', $this->taxonomy->names['name'])));
            }

            if ($key === 'slug') {
                // create a slug friendly name
                $name = strtolower(str_replace([' ', '_'], '-', $this->taxonomy->names['name']));
            }

            // if is plural or slug, append an 's'
            if (in_array($key, ['plural', 'slug'])) {
                $name .= 's';
            }

            // asign the name to the PostType property
            $this->taxonomy->$key = $name;
        }
    }

    /**
     * Create options for Taxonomy.
     *
     * @return array
     */
    public function createOptions()
    {
        // Default options.
        $options = [
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => [
                'slug' => $this->taxonomy->slug,
            ],
        ];

        // Replace defaults with the options passed.
        $options = array_replace_recursive($options, $this->taxonomy->options);

        // Create and set labels.
        if (!isset($options['labels'])) {
            $options['labels'] = $this->createLabels();
        }

        return $options;
    }

    /**
     * Create labels for the Taxonomy.
     *
     * @return array
     */
    public function createLabels()
    {
        // Default labels.
        $labels = [
            'name' => $this->taxonomy->plural,
            'singular_name' => $this->taxonomy->singular,
            'menu_name' => $this->taxonomy->plural,
            'all_items' => "All {$this->taxonomy->plural}",
            'edit_item' => "Edit {$this->taxonomy->singular}",
            'view_item' => "View {$this->taxonomy->singular}",
            'update_item' => "Update {$this->taxonomy->singular}",
            'add_new_item' => "Add New {$this->taxonomy->singular}",
            'new_item_name' => "New {$this->taxonomy->singular} Name",
            'parent_item' => "Parent {$this->taxonomy->plural}",
            'parent_item_colon' => "Parent {$this->taxonomy->plural}:",
            'search_items' => "Search {$this->taxonomy->plural}",
            'popular_items' => "Popular {$this->taxonomy->plural}",
            'separate_items_with_commas' => "Seperate {$this->taxonomy->plural} with commas",
            'add_or_remove_items' => "Add or remove {$this->taxonomy->plural}",
            'choose_from_most_used' => "Choose from most used {$this->taxonomy->plural}",
            'not_found' => "No {$this->taxonomy->plural} found",
        ];

        return array_replace($labels, $this->taxonomy->labels);
    }

    /**
     * Modify the columns for the Taxonomy.
     *
     * @param  array  $columns  The WordPress default columns.
     *
     * @return array
     */
    public function modifyColumns($columns)
    {
        $columns = $this->taxonomy->columns->modifyColumns($columns);

        return $columns;
    }

    /**
     * Populate custom columns for the Taxonomy.
     *
     * @param  string $content The column content.
     * @param  string $column  The current column name.
     * @param  int    $term_id The current term ID.
     *
     * @return string
     */
    public function populateColumns($content, $column, $term_id)
    {
        if (isset($this->taxonomy->columns->populate[$column])) {
            $content = call_user_func_array($this->taxonomy->columns()->populate[$column], [$content, $column, $term_id]);
        }

        return $content;
    }

    /**
     * Make custom columns sortable.
     *
     * @param array $columns Default WordPress sortable columns.
     *
     * @return array
     */
    public function setSortableColumns($columns)
    {
        if (!empty($this->taxonomy->columns()->sortable)) {
            $columns = array_merge($columns, $this->taxonomy->columns()->sortable);
        }

        return $columns;
    }

    /**
     * Set query to sort custom columns.
     *
     * @param WP_Term_Query $query The admin WP_Query.
     *
     * @return void
     */
    public function sortSortableColumns($query)
    {
        // Don't modify the query if we're not in the post type admin.
        if (!is_admin() || !in_array($this->taxonomy->name, $query->query_vars['taxonomy'])) {
            return;
        }

        // Check the orderby is a custom ordering.
        if (isset($_GET['orderby']) && array_key_exists($_GET['orderby'], $this->taxonomy->columns()->sortable)) {
            // Get the custom sorting options.
            $meta = $this->taxonomy->columns()->sortable[$_GET['orderby']];

            // Check ordering is not numeric.
            if (is_string($meta)) {
                $meta_key = $meta;
                $orderby = 'meta_value';
            } else {
                $meta_key = $meta[0];
                $orderby = 'meta_value_num';
            }

            // Set the sort order.
            $query->query_vars['orderby'] = $orderby;
            $query->query_vars['meta_key'] = $meta_key;
        }
    }
}
