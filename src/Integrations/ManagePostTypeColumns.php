<?php

namespace PostTypes\Integrations;

use PostTypes\Columns;
use PostTypes\Contracts\PostTypeContract;
use WP_Query;

class ManagePostTypeColumns
{
    /**
     * PostType to register.
     *
     * @var PostTypeContract
     */
    protected $posttype;

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
     * Add hooks.
     *
     * @return void
     */
    public function register(): void
    {
        $name = $this->posttype->name();

        add_action('init', [$this, 'createColumns'], 10, 0);
        add_filter('manage_' . $name . '_posts_columns', [$this, 'modifyColumns'], 10, 1);
        add_action('manage_' . $name . '_posts_custom_column', [$this, 'populateColumns'], 10, 2);
        add_filter('manage_edit-' . $name . '_sortable_columns', [$this, 'setSortableColumns'], 10, 1);
        add_action('pre_get_posts', [$this, 'sortSortableColumns'], 10, 1);
    }

    /**
     * Create Columns.
     *
     * @return void
     */
    public function createColumns(): void
    {
        $this->columns = $this->posttype->columns(new Columns());
    }

    /**
     * Modify the PostType columns.
     *
     * @param array $columns
     * @return array
     */
    public function modifyColumns(array $columns): array
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
    public function populateColumns(string $column, int $post_id): void
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
    public function setSortableColumns(array $columns): array
    {
        $sortable = $this->columns->getSortableColumns();

        return array_merge($columns, $sortable);
    }

    /**
     * Sort PostType columns.
     *
     * @param WP_Query $query
     * @return void
     */
    public function sortSortableColumns(WP_Query $query): void
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
