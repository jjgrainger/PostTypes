<?php

namespace PostTypes\Registrars;

use PostTypes\Contracts\TaxonomyContract;
use PostTypes\Columns;

class TaxonomyRegistrar
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

        $this->columns = $taxonomy->columns(new Columns());
    }

    /**
     * Register the Taxonomy to WordPress.
     *
     * @return void
     */
    public function register()
    {
        $name = $this->taxonomy->name();

        add_action('init', [$this, 'registerTaxonomy'], 9);
        add_action('init', [$this, 'registerTaxonomyToPostTypes'], 10);

        // Handle Taxonomy columns.
        add_filter('manage_edit-' . $name . '_columns', [$this, 'modifyColumns'], 10, 1);
        add_filter('manage_' . $name . '_custom_column', [$this, 'populateColumns'], 10, 3);
        add_filter('manage_edit-' . $name . '_sortable_columns', [$this, 'setSortableColumns'], 10, 1);
        add_action('parse_term_query', [$this, 'sortSortableColumns'], 10, 1);
    }

    /**
     * Register the Taxonomy.
     *
     * @return void
     */
    public function registerTaxonomy()
    {
        register_taxonomy($this->taxonomy->name(), null, $this->generateOptions());
    }

    /**
     * Generate Taxonomy options.
     *
     * @return array
     */
    public function generateOptions()
    {
        $defaults = [
            'public'            => true,
            'show_in_rest'      => true,
            'hierarchical'      => true,
            'show_admin_column' => true,
            'labels'            => $this->taxonomy->labels(),
            'rewrite'           => [
                'slug' => $this->taxonomy->slug(),
            ],
        ];

        return array_replace_recursive($defaults, $this->taxonomy->options());
    }

    /**
     * Register Taxonomy to post types.
     *
     * @return void
     */
    public function registerTaxonomyToPostTypes()
    {
        foreach ($this->taxonomy->posttypes() as $posttype) {
            register_taxonomy_for_object_type($this->taxonomy->name(), $posttype);
        }
    }

    /**
     * Modify the Taxonomy columns.
     *
     * @param array $columns
     * @return array
     */
    public function modifyColumns(array $columns)
    {
        return $this->columns->applyColumns($columns);
    }

    /**
     * Populate Taxonomy column.
     *
     * @param string $content
     * @param string $column
     * @param int $term_id
     * @return void
     */
    public function populateColumns($content, $column, $term_id)
    {
        $this->columns->populateColumn($column, [$term_id, $content]);
    }

    /**
     * Set the Taxonomy sortable columns.
     *
     * @param array $columns
     * @return array
     */
    public function setSortableColumns($columns)
    {
        return $this->columns->setSortable($columns);
    }

    /**
     * Sort Taxonomy column.
     *
     * @param \WP_Term_Query $query
     * @return void
     */
    public function sortSortableColumns($query)
    {
        if (!is_admin() || !in_array($this->taxonomy->name(), $query->query_vars['taxonomy'])) {
            return;
        }

        $column = $query->query_vars['orderby'];

        $this->columns->sortColumn($column, $query);
    }
}
