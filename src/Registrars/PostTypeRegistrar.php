<?php

namespace PostTypes\Registrars;

use PostTypes\Contracts\PostTypeContract;
use PostTypes\Columns;

class PostTypeRegistrar
{
    /**
     * PostType to register.
     *
     * @var PostTypeContract
     */
    private $posttype;

    /**
     * The PostType columns.
     *
     * @var Columns
     */
    private $columns;

    /**
     * Constructor.
     *
     * @param PostTypeContract $posttype
     */
    public function __construct(PostTypeContract $posttype)
    {
        $this->posttype = $posttype;

        $this->columns = $posttype->columns(new Columns());
    }

    /**
     * Register the PostType to WordPress.
     *
     * @return void
     */
    public function register()
    {
        $name = $this->posttype->name();

        if (post_type_exists($name)) {
            // Modify the existing PostType if it exists.
            add_filter('register_post_type_args', [$this, 'modifyPostType'], 10, 2);
        } else {
            // Register the new PostType to WordPress.
            add_action('init', [$this, 'registerPostType'], 10, 0);
        }

        // Handle PostType filters.
        add_action('restrict_manage_posts', [$this, 'modifyFilters'], 10, 2);

        // Handle PostType columns.
        add_filter('manage_' . $name . '_posts_columns', [$this, 'modifyColumns'], 10, 1);
        add_filter('manage_' . $name . '_posts_custom_column', [$this, 'populateColumns'], 10, 2);
        add_filter('manage_edit-' . $name . '_sortable_columns', [$this, 'setSortableColumns'], 10, 1);
        add_action('pre_get_posts', [$this, 'sortSortableColumns'], 10, 1);

        // Register custom hooks.
        $this->posttype->hooks();
    }

    /**
     * Register the PostType.
     *
     * @return void
     */
    public function registerPostType()
    {
        register_post_type($this->posttype->name(), $this->generateOptions());
    }

    /**
     * Modify the existing PostType.
     *
     * @param array $args
     * @param string $posttype
     * @return array
     */
    public function modifyPostType(array $args, string $posttype)
    {
        if ($posttype !== $this->posttype->name()) {
            return $args;
        }

        // create options for the PostType.
        $options = $this->generateOptions();

        return array_replace_recursive($args, $options);
    }

    /**
     * Generate the options for the PostType.
     *
     * @return array
     */
    public function generateOptions()
    {
        $defaults = [
            'public'       => true,
            'show_in_rest' => true,
            'labels'       => $this->posttype->labels(),
            'taxonomies'   => $this->posttype->taxonomies(),
            'supports'     => $this->posttype->supports(),
            'menu_icon'    => $this->posttype->icon(),
            'rewrite'      => [
                'slug' => $this->posttype->slug(),
            ],
        ];

        return array_replace_recursive($defaults, $this->posttype->options());
    }

    /**
     * Modify the PostType filters.
     *
     * @param string $posttype
     * @return void
     */
    public function modifyFilters($posttype)
    {
        if ($posttype !== $this->posttype->name()) {
            return;
        }

        foreach ($this->posttype->filters() as $taxonomy) {
            if (!is_object_in_taxonomy($posttype, $taxonomy)) {
                continue;
            }

            $query_var = get_taxonomy($taxonomy)->query_var;
            $selected = isset($_GET[$query_var]) ? $_GET[$query_var] : '';

            $options = [
                'name'            => $query_var, //$taxonomy,
                'value_field'     => 'slug',
                'taxonomy'        => $taxonomy,
                'show_option_all' => get_taxonomy($taxonomy)->labels->all_items,
                'hierarchical'    => get_taxonomy($taxonomy)->hierarchical,
                'hide_empty'      => 0,
                'show_count'      => 0,
                'orderby'         => 'name',
                'selected'        => $selected, //isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '',
            ];

            echo '<label class="screen-reader-text" for="' . $taxonomy . '">';
            echo get_taxonomy($taxonomy)->labels->filter_by_item;
            echo '</label>';

            wp_dropdown_categories($options);
        }
    }

    /**
     * Modify the PostType columns.
     *
     * @param array $columns
     * @return array
     */
    public function modifyColumns(array $columns)
    {
        return $this->columns->applyColumns($columns);
    }

    /**
     * Populate the PostType columns.
     *
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function populateColumns($column, $post_id)
    {
        $this->columns->populateColumn($column, [$post_id]);
    }

    /**
     * Set the PostTypes sortable columns.
     *
     * @param array $columns
     * @return array
     */
    public function setSortableColumns($columns)
    {
        return $this->columns->setSortable($columns);
    }

    /**
     * Sort PostType columns.
     *
     * @param \WP_Query $query
     * @return void
     */
    public function sortSortableColumns($query)
    {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        $column = $query->get('orderby');

        $this->columns->sortColumn($column, $query);
    }
}
