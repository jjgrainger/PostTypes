<?php

namespace PostTypes;

/**
 * Columns
 *
 * Used to help manage a post types columns in the admin table
 *
 * @link http://github.com/jjgrainger/PostTypes/
 * @author  jjgrainger
 * @link    http://jjgrainger.co.uk
 * @version 1.1.1
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */
class Columns
{
    /**
     * Holds an array of all the defined columns.
     *
     * @var array
     */
    public $items = [];

    /**
     * An array of columns to add.
     *
     * @var array
     */
    public $add = [];

    /**
     * An array of columns to hide.
     *
     * @var array
     */
    public $hide = [];

    /**
     * An array of columns to reposition.
     *
     * @var array
     */
    public $positions = [];

    /**
     * An array of custom populate callbacks.
     *
     * @var array
     */
    public $populate = [];

    /**
     * An array of columns that are sortable.
     *
     * @var array
     */
    public $sortable = [];

    /**
     * Set the all columns
     * @param array $columns an array of all the columns to replace
     */
    public function set($columns)
    {
        $this->items = $columns;
    }

    /**
     * Add a new column
     * @param string  $column   the slug of the column
     * @param string  $label    the label for the column
     */
    public function add($columns, $label = null)
    {

        if (!is_array($columns)) {
            $columns = [$columns => $label];
        }

        foreach ($columns as $column => $label) {
            if (is_null($label)) {
                $label = str_replace(['_', '-'], ' ', ucfirst($column));
            }

            $this->add[$column] = $label;
        }
    }

    /**
     * Add a column to hide
     * @param  string $column the slug of the column to hdie
     */
    public function hide($columns)
    {
        if (!is_array($columns)) {
            $columns = [$columns];
        }

        foreach ($columns as $column) {
            $this->hide[] = $column;
        }
    }

    /**
     * Set a custom callback to populate a column
     * @param  string $column   the column slug
     * @param  mixed  $callback callback function
     */
    public function populate($column, $callback)
    {
        $this->populate[$column] = $callback;
    }

    /**
     * Define the postion for a columns
     * @param  string  $columns  an array of columns
     */
    public function order($columns)
    {
        foreach ($columns as $column => $position) {
            $this->positions[$column] = $position;
        }
    }

    /**
     * Set columns that are sortable
     * @param  string  $column     the slug of the column
     * @param  string  $meta_value the meta_value to orderby
     * @param  boolean $is_num     whether to order by string/number
     */
    public function sortable($sortable)
    {
        foreach ($sortable as $column => $options) {
            $this->sortable[$column] = $options;
        }
    }
}
