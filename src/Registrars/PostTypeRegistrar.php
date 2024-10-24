<?php

namespace PostTypes\Registrars;

use PostTypes\PostType;

class PostTypeRegistrar
{
    protected $posttype;

    public function __construct(PostType $posttype)
    {
        $this->posttype = $posttype;
    }

    public function register()
    {
        // Get the PostType name.
        $name = $this->posttype->name;

        // Register the PostType
        if (!post_type_exists($name)) {
            add_action('init', [$this, 'registerPostType'], 10);
        } else {
            add_filter('register_post_type_args', [$this, 'modifyPostType'], 10, 2);
        }

        // register Taxonomies to the PostType
        add_action('init', [$this, 'registerTaxonomies'], 10);

        // modify filters on the admin edit screen
        add_action('restrict_manage_posts', [$this, 'modifyFilters'], 10, 1);

        if (isset($this->posttype->columns)) {
            // modify the admin edit columns.
            add_filter('manage_' . $name . '_posts_columns', [$this, 'modifyColumns'], 10, 1);

            // populate custom columns
            add_filter('manage_' . $name . '_posts_custom_column', [$this, 'populateColumns'], 10, 2);

            // run filter to make columns sortable.
            add_filter('manage_edit-' . $name . '_sortable_columns', [$this, 'setSortableColumns'], 10, 1);

            // run action that sorts columns on request.
            add_action('pre_get_posts', [$this, 'sortSortableColumns'], 10, 1);
        }
    }

    /**
     * Register the PostType
     * @return void
     */
    public function registerPostType()
    {
        // create options for the PostType
        $options = $this->posttype->createOptions();

        // check that the post type doesn't already exist
        if (!post_type_exists($this->posttype->name)) {
            // register the post type
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

        // create options for the PostType
        $options = $this->posttype->createOptions();

        return array_replace_recursive($args, $options);
    }

    /**
     * Register Taxonomies to the PostType
     * @return void
     */
    public function registerTaxonomies()
    {
        if (empty($this->posttype->taxonomies)) {
            return;
        }

        foreach ($this->posttype->taxonomies as $taxonomy) {
            register_taxonomy_for_object_type($taxonomy, $this->posttype->name);
        }
    }

    /**
     * Modify and display filters on the admin edit screen
     * @param  string $posttype The current screen post type
     * @return void
     */
    public function modifyFilters($posttype)
    {
        // first check we are working with the this PostType
        if ($posttype === $this->posttype->name) {
            // calculate what filters to add
            $filters = $this->posttype->getFilters();

            foreach ($filters as $taxonomy) {
                // if the taxonomy doesn't exist, ignore it
                if (!taxonomy_exists($taxonomy)) {
                    continue;
                }

                // If the taxonomy is not registered to the post type, continue.
                if (!is_object_in_taxonomy($this->posttype->name, $taxonomy)) {
                    continue;
                }

                // get the taxonomy object
                $tax = get_taxonomy($taxonomy);

                // start the html for the filter dropdown
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
                sprintf(
                    '<label class="screen-reader-text" for="%s">%s</label>',
                    $taxonomy,
                    $tax->labels->filter_by_item
                );

                // Output dropdown for taxonomy.
                wp_dropdown_categories($dropdown_args);
            }
        }
    }

    /**
     * Modify the columns for the PostType
     * @param  array  $columns  Default WordPress columns
     * @return array            The modified columns
     */
    public function modifyColumns($columns)
    {
        return $this->posttype->columns->modifyColumns($columns);
    }
    /**
     * Populate custom columns for the PostType
     * @param  string $column   The column slug
     * @param  int    $post_id  The post ID
     */
    public function populateColumns($column, $post_id)
    {
        if (isset($this->posttype->columns->populate[$column])) {
            call_user_func_array($this->posttype->columns()->populate[$column], [$column, $post_id]);
        }
    }

    /**
     * Make custom columns sortable
     * @param array  $columns  Default WordPress sortable columns
     */
    public function setSortableColumns($columns)
    {
        if (!empty($this->posttype->columns()->sortable)) {
            $columns = array_merge($columns, $this->posttype->columns()->sortable);
        }

        return $columns;
    }

    /**
     * Set query to sort custom columns
     * @param  WP_Query $query
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
