<?php

namespace PostTypes\Integrations;

use PostTypes\Contracts\TaxonomyContract;
use PostTypes\Columns;
use WP_Tax_Query;
use WP_Term_Query;

class ManageTaxonomyColumns
{
    /**
     * Taxonomy to register.
     *
     * @var TaxonomyContract
     */
    private $taxonomy;

    /**
     * Taxonomy Columns.
     *
     * @var Columns
     */
    private $columns;

    /**
     * Constructor.
     *
     * @param TaxonomyContract $taxonomy
     */
    public function __construct(TaxonomyContract $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * Register the Taxonomy to WordPress.
     *
     * @return void
     */
    public function register(): void
    {
        $name = $this->taxonomy->name();

        add_action('init', [$this, 'createcolumns'], 10);
        add_filter('manage_edit-' . $name . '_columns', [$this, 'modifyColumns'], 10, 1);
        add_action('manage_' . $name . '_custom_column', [$this, 'populateColumns'], 10, 3);
        add_filter('manage_edit-' . $name . '_sortable_columns', [$this, 'setSortableColumns'], 10, 1);
        add_action('parse_term_query', [$this, 'sortSortableColumns'], 10, 1);
    }

    /**
     * Create Columns.
     *
     * @return void
     */
    public function createColumns(): void
    {
        $this->columns = $this->taxonomy->columns(new Columns());
    }

    /**
     * Modify the Taxonomy columns.
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
     * Populate Taxonomy column.
     *
     * @param string $content
     * @param string $column
     * @param int $term_id
     * @return void
     */
    public function populateColumns(string $content, string $column, int $term_id): void
    {
        $callback = $this->columns->getPopulateCallback($column);

        if ($callback) {
            call_user_func_array($callback, [$term_id, $content]);
        }
    }

    /**
     * Set the Taxonomy sortable columns.
     *
     * @param array $columns
     * @return array
     */
    public function setSortableColumns(array $columns): array
    {
        return array_merge($columns, $this->columns->getSortableColumns());
    }

    /**
     * Sort Taxonomy column.
     *
     * @param \WP_Term_Query $query
     * @return void
     */
    public function sortSortableColumns(WP_Term_Query $query): void
    {
        if (!is_admin() ||
            !is_array($query->query_vars['taxonomy']) ||
            !in_array($this->taxonomy->name(), $query->query_vars['taxonomy'])
        ) {
            return;
        }

        $column = $query->query_vars['orderby'];
        $callback = $this->columns->getSortCallback($column);

        if ($callback) {
            call_user_func_array($callback, [$query]);
        }
    }
}
