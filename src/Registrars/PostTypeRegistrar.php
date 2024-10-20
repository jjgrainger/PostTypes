<?php

namespace PostTypes\Registrars;

use PostTypes\PostType;

class PostTypeRegistrar
{
    /**
     * Constructor.
     *
     * @param PostType $posttype The PostType class to register to WordPress.
     *
     * @return void
     */
    public function __construct(PostType $posttype)
    {
        $this->posttype = $posttype;
    }

     /**
     * Register the PostType to WordPress.
     *
     * @return void
     */
    public function register()
    {
        // Create the names required to register the post type.
        $this->createNames();

        // Register the PostType.
        if (!post_type_exists($this->posttype->name)) {
            add_action('init', [$this, 'registerPostType']);
        } else {
            add_filter('register_post_type_args', [$this, 'modifyPostType'], 10, 2);
        }

        // Register Taxonomies to the PostType.
        add_action('init', [$this, 'registerTaxonomies']);

        // Modify filters on the admin edit screen.
        add_action('restrict_manage_posts', [$this, 'modifyFilters']);

        if (isset($this->posttype->columns)) {
            // Modify the admin edit columns.
            add_filter("manage_{$this->posttype->name}_posts_columns", [$this, 'modifyColumns'], 10, 1);

            // Populate custom columns
            add_filter("manage_{$this->posttype->name}_posts_custom_column", [$this, 'populateColumns'], 10, 2);

            // Run filter to make columns sortable.
            add_filter('manage_edit-'.$this->posttype->name.'_sortable_columns', [$this, 'setSortableColumns']);

            // Run action that sorts columns on request.
            add_action('pre_get_posts', [$this, 'sortSortableColumns']);
        }
    }

    /**
     * Register the new Post Type.
     *
     * @return void
     */
    public function registerPostType()
    {
        // Create options for the PostType.
        $options = $this->createOptions();

        // Check that the post type doesn't already exist.
        if (!post_type_exists($this->posttype->name)) {
            // Register the post type.
            register_post_type($this->posttype->name, $options);
        }
    }

    /**
     * Modify the existing Post Type.
     *
     * @return array
     */
    public function modifyPostType(array $args, string $posttype)
    {
        if ($posttype !== $this->posttype->name) {
            return $args;
        }

        // Create options for the PostType.
        $options = $this->createOptions();

        $args = array_replace_recursive($args, $options);

        return $args;
    }

    /**
     * Create the required names for the PostType.
     *
     * @return void
     */
    public function createNames()
    {
        // Names required for the PostType.
        $required = [
            'name',
            'singular',
            'plural',
            'slug',
        ];

        foreach ($required as $key) {
            // If the name is set, assign it.
            if (isset($this->posttype->names[$key])) {
                $this->posttype->$key = $this->posttype->names[$key];
                continue;
            }

            // If the key is not set and is singular or plural.
            if (in_array($key, ['singular', 'plural'])) {
                // create a human friendly name
                $name = ucwords(strtolower(str_replace(['-', '_'], ' ', $this->posttype->names['name'])));
            }

            if ($key === 'slug') {
                // Create a slug friendly name.
                $name = strtolower(str_replace([' ', '_'], '-', $this->posttype->names['name']));
            }

            // If is plural or slug, append an 's'.
            if (in_array($key, ['plural', 'slug'])) {
                if (substr($name, strlen($name) - 1, 1) == "y") {
                    $name = substr($name, 0, strlen($name) - 1) . "ies";
                } else {
                    $name .= 's';
                }
            }

            // Asign the name to the PostType property.
            $this->posttype->$key = $name;
        }
    }

    /**
     * Create options for PostType.
     *
     * @return array
     */
    public function createOptions()
    {
        // Default options.
        $options = [
            'public' => true,
            'rewrite' => [
                'slug' => $this->posttype->slug
            ]
        ];

        // Replace defaults with the options passed.
        $options = array_replace_recursive($options, $this->posttype->options);

        // Create and set labels.
        if (!isset($options['labels'])) {
            $options['labels'] = $this->createLabels();
        }

        // Set the menu icon.
        if (!isset($options['menu_icon']) && isset($this->posttype->icon)) {
            $options['menu_icon'] = $this->posttype->icon;
        }

        return $options;
    }

    /**
     * Create the labels for the PostType.
     *
     * @return array
     */
    public function createLabels()
    {
        // Default labels.
        $labels = [
            'name' => $this->posttype->plural,
            'singular_name' => $this->posttype->singular,
            'menu_name' => $this->posttype->plural,
            'all_items' => $this->posttype->plural,
            'add_new' => "Add New",
            'add_new_item' => "Add New {$this->posttype->singular}",
            'edit_item' => "Edit {$this->posttype->singular}",
            'new_item' => "New {$this->posttype->singular}",
            'view_item' => "View {$this->posttype->singular}",
            'search_items' => "Search {$this->posttype->plural}",
            'not_found' => "No {$this->posttype->plural} found",
            'not_found_in_trash' => "No {$this->posttype->plural} found in Trash",
            'parent_item_colon' => "Parent {$this->posttype->singular}:",
        ];

        return array_replace_recursive($labels, $this->posttype->labels);
    }

    /**
     * Register Taxonomies to the PostType.
     *
     * @return void
     */
    public function registerTaxonomies()
    {
        if (!empty($this->posttype->taxonomies)) {
            foreach ($this->posttype->taxonomies as $taxonomy) {
                register_taxonomy_for_object_type($taxonomy, $this->posttype->name);
            }
        }
    }

    /**
     * Modify and display filters on the admin edit screen.
     *
     * @param  string $posttype The current screen post type.
     *
     * @return void
     */
    public function modifyFilters($posttype)
    {
        // First check we are working with the this PostType.
        if ($posttype === $this->posttype->name) {
            // Calculate what filters to add.
            $filters = $this->getFilters();

            foreach ($filters as $taxonomy) {
                // If the taxonomy doesn't exist, ignore it.
                if (!taxonomy_exists($taxonomy)) {
                    continue;
                }

                // If the taxonomy is not registered to the post type, continue.
                if (!is_object_in_taxonomy($this->posttype->name, $taxonomy)) {
                    continue;
                }

                // Get the taxonomy object.
                $tax = get_taxonomy($taxonomy);

                // Start the html for the filter dropdown.
                $selected = null;

                if (isset($_GET[$taxonomy])) {
                    $selected = sanitize_title($_GET[$taxonomy]);
                }

                $dropdown_args = [
                    'name'            => $taxonomy,
                    'value_field'     => 'slug',
                    'taxonomy'        => $tax->name,
                    'show_option_all' => $tax->labels->all_items,
                    'hierarchical'    => $tax->hierarchical,
                    'selected'        => $selected,
                    'orderby'         => 'name',
                    'hide_empty'      => 0,
                    'show_count'      => 0,
                ];

                // Output screen reader label.
                echo '<label class="screen-reader-text" for="cat">' . $tax->labels->filter_by_item . '</label>';

                // Output dropdown for taxonomy.
                wp_dropdown_categories($dropdown_args);
            }
        }
    }

    /**
     * Calculate the filters for the PostType.
     *
     * @return array
     */
    public function getFilters()
    {
        // Default filters are empty.
        $filters = [];

        // If custom filters have been set, use them.
        if (!is_null($this->posttype->filters)) {
            return $this->posttype->filters;
        }

        // If no custom filters have been set, and there are Taxonomies assigned to the PostType.
        if (is_null($this->posttype->filters) && !empty($this->posttype->taxonomies)) {
            // Create filters for each taxonomy assigned to the PostType.
            return $this->posttype->taxonomies;
        }

        return $filters;
    }

    /**
     * Modify the columns for the PostType.
     *
     * @param  array $columns Default WordPress columns.
     *
     * @return array
     */
    public function modifyColumns($columns)
    {
        $columns = $this->posttype->columns->modifyColumns($columns);

        return $columns;
    }

    /**
     * Populate custom columns for the PostType.
     *
     * @param  string $column   The column slug.
     * @param  int    $post_id  The post ID.
     *
     * @return void
     */
    public function populateColumns($column, $post_id)
    {
        if (isset($this->posttype->columns->populate[$column])) {
            call_user_func_array($this->posttype->columns()->populate[$column], [$column, $post_id]);
        }
    }

    /**
     * Make custom columns sortable.
     *
     * @param array  $columns  Default WordPress sortable columns.
     *
     * @return array
     */
    public function setSortableColumns($columns)
    {
        if (!empty($this->posttype->columns()->sortable)) {
            $columns = array_merge($columns, $this->posttype->columns()->sortable);
        }

        return $columns;
    }

    /**
     * Set query to sort custom columns.
     *
     * @param WP_Query $query The admin screen WP_Query.
     *
     * @return void
     */
    public function sortSortableColumns($query)
    {
        // don't modify the query if we're not in the post type admin
        if (!is_admin() || $query->get('post_type') !== $this->posttype->name) {
            return;
        }

        $orderby = $query->get('orderby');

        // if the sorting a custom column
        if ($this->posttype->columns()->isSortable($orderby)) {
            // get the custom column options
            $meta = $this->posttype->columns()->sortableMeta($orderby);

            // determine type of ordering
            if (is_string($meta) or !$meta[1]) {
                $meta_key = $meta;
                $meta_value = 'meta_value';
            } else {
                $meta_key = $meta[0];
                $meta_value = 'meta_value_num';
            }

            // set the custom order
            $query->set('meta_key', $meta_key);
            $query->set('orderby', $meta_value);
        }
    }
}
