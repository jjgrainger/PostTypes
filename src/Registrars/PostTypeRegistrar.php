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
    }

    /**
     * Register the PostType to WordPress.
     *
     * @return void
     */
    public function register()
    {
        $name = $this->posttype->name();

        // Initialize the post type.
        add_action('init', [$this, 'createColumns'], 10, 0);
        add_action('init', [$this, 'initialize'], 10, 0);

        // Handle PostType filters.
        add_action('restrict_manage_posts', [$this, 'modifyFilters'], 10, 1);

        // Handle PostType columns.
        add_filter('manage_' . $name . '_posts_columns', [$this, 'modifyColumns'], 10, 1);
        add_action('manage_' . $name . '_posts_custom_column', [$this, 'populateColumns'], 10, 2);
        add_filter('manage_edit-' . $name . '_sortable_columns', [$this, 'setSortableColumns'], 10, 1);
        add_action('pre_get_posts', [$this, 'sortSortableColumns'], 10, 1);

        // Register custom hooks.
        $this->posttype->hooks();
    }

    /**
     * Create Columns.
     *
     * @return void
     */
    public function createColumns()
    {
        $this->columns = $this->posttype->columns(new Columns());
    }

    /**
     * Register Post Type.
     *
     * @return void
     */
    public function initialize()
    {
        // Modify the existing PostType if it exists.
        if (post_type_exists($this->posttype->name())) {
            add_filter('register_post_type_args', [$this, 'modifyPostType'], 10, 2);

            return;
        }

        // Register the new PostType to WordPress.
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

        return array_replace_recursive($args, $this->generateOptions());
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
        foreach ($this->columns->getColumns() as $key => $label) {
            $columns[$key] = $label;
        }

        if ($remove = $this->columns->getRemoved()) {
            $columns = array_diff_key($columns, array_flip($remove));
        }

        if ($only = $this->columns->getOnly()) {
            $columns = array_intersect_key($columns, array_flip($only));
        }

        foreach ($this->columns->getPositions() as $key => $position) {
            [$direction, $reference] = $position;

            if (!isset($direction) || !isset($reference)) {
                continue;
            }

            $new = [];

            foreach ($columns as $k => $label) {
                if ('before' === $direction && $k === $reference) {
                    $new[$key] = $columns[$key];
                }

                $new[$k] = $label;

                if ('after' === $direction && $k === $reference) {
                    $new[$key] = $columns[$key];
                }
            }

            $columns = $new;
        }

        return $columns;
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
        $callback = $this->columns->getPopulateCallback($column);

        if ($callback) {
            call_user_func_array($callback, [$post_id]);
        }
    }

    /**
     * Set the PostTypes sortable columns.
     *
     * @param array $columns
     * @return array
     */
    public function setSortableColumns($columns)
    {
        $sortable = $this->columns->getSortableColumns();

        return array_merge($columns, $sortable);
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
        $callback = $this->columns->getSortCallback($column);

        if ($callback) {
            call_user_func_array($callback, [$query]);
        }
    }
}
