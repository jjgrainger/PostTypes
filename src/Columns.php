<?php

namespace PostTypes;

use PostTypes\Contracts\ColumnContract;

class Columns
{
    /**
     * Columns to add.
     *
     * @var array
     */
    public $add = [];

    /**
     * Column populate callbacks.
     *
     * @var array
     */
    public $populate = [];

    /**
     * Columns to remove.
     *
     * @var array
     */
    public $remove = [];

    /**
     * Columns order.
     *
     * @var array
     */
    public $order = [];

    /**
     * Sortable columns and sort callbacks.
     *
     * @var array
     */
    public $sortable = [];

    /**
     * Add a column object.
     *
     * @param ColumnContract $column
     * @return void
     */
    public function column(ColumnContract $column)
    {
        $this->add($column->name(), $column->label());

        $this->populate($column->name(), [$column, 'populate']);

        if (!is_null($column->order())) {
            $this->order[$column->name()] = $column->order();
        }

        if ($column->isSortable()) {
            $this->sortable($column->name(), [$column, 'sort']);
        }
    }

    /**
     * Add a column.
     *
     * @param string $key
     * @param string $label
     * @param callable|null $callback
     * @return void
     */
    public function add(string $key, string $label, callable $callback = null)
    {
        $this->add[$key] = $label;

        if (is_callable($callback)) {
            $this->populate($key, $callback);
        }
    }

    /**
     * Set column populate callback.
     *
     * @param string $key
     * @param callable $callback
     * @return void
     */
    public function populate(string $key, callable $callback)
    {
        $this->populate[$key] = $callback;
    }

    /**
     * Remove columns.
     *
     * @param array $keys
     * @return void
     */
    public function remove(array $keys)
    {
        $this->remove = array_merge($this->remove, $keys);
    }

    /**
     * Set columns order
     *
     * @param array $order
     * @return void
     */
    public function order(array $order)
    {
        $this->order = array_merge($this->order, $order);
    }

    /**
     * Set sortable columns and sort callback.
     *
     * @param string $key
     * @param callable $callback
     * @return void
     */
    public function sortable(string $key, callable $callback)
    {
        $this->sortable[$key] = $callback;
    }

    /**
     * Apply columns.
     *
     * @param array $columns
     * @return void
     */
    public function applyColumns(array $columns)
    {
        if (!empty($this->add)) {
            $columns = array_merge($columns, $this->add);
        }

        if (!empty($this->remove)) {
            $columns = array_diff_key($columns, array_flip($this->remove));
        }

        if (!empty($this->order)) {
            $order = $this->order;

            // Sort the order array.
            asort($order);

            // Flip order so the index is the position.
            $order = array_flip($order);

            // Create the current order array.
            $current = array_keys($columns);

            // Loop over the order.
            foreach ($order as $index => $key) {
                array_splice($current, $index, 0, $key);
            }

            $new = array_flip(array_unique($current));

            $columns = array_merge($new, $columns);
        }

        return $columns;
    }

    /**
     * Populate a column.
     *
     * @param string $column
     * @param array $params
     * @return void
     */
    public function populateColumn(string $column, array $params)
    {
        if (isset($this->populate[$column]) && is_callable($this->populate[$column])) {
            call_user_func_array($this->populate[$column], $params);
        }
    }

    /**
     * Set sortable columns
     *
     * @param array $columns
     * @return array
     */
    public function setSortable(array $columns): array
    {
        foreach (array_keys($this->sortable) as $key) {
            $columns[$key] = $key;
        }

        return $columns;
    }

    /**
     * Sort a column.
     *
     * @param string $column
     * @param \WP_Query|\WP_Term_Query $query
     * @return void
     */
    public function sortColumn(string $column, $query)
    {
        if (isset($this->sortable[$column]) && is_callable($this->sortable[$column])) {
            call_user_func_array($this->sortable[$column], [$query]);
        }
    }
}
