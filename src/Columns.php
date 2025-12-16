<?php

namespace PostTypes;

use InvalidArgumentException;
use PostTypes\Contracts\ColumnContract;

class Columns
{
    /**
     * Columns keys and labels.
     *
     * @var array
     */
    protected $labels = [];

    /**
     * Columns to remove.
     *
     * @var array
     */
    protected $remove = [];

    /**
     * Columns whitelist.
     *
     * @var array
     */
    protected $only = [];

    /**
     * Column positions.
     *
     * @var array
     */
    protected $positions = [];

    /**
     * Column populate callbacks.
     *
     * @var array
     */
    protected $populateCallbacks = [];

    /**
     * Sortable columns and sort callbacks.
     *
     * @var array
     */
    protected $sortCallbacks = [];

    /**
     * Create a new Column.
     *
     * @param string $key
     * @return ColumnBuilder
     */
    public function add(string $key): ColumnBuilder
    {
        return new ColumnBuilder($this, $key);
    }

    /**
     * Modify an existing column.
     *
     * @param string $key
     * @return ColumnBuilder
     */
    public function modify(string $key): ColumnBuilder
    {
        return $this->add($key);
    }

    /**
     * Add a column object.
     *
     * @param ColumnContract $column
     * @return void
     */
    public function column(ColumnContract $column): void
    {
        $this->label($column->name(), $column->label());

        if (!is_null($column->position())) {
            [$direction, $reference] = $column->position();

            $this->position($column->name(), $direction, $reference);
        }

        if ($callback = $column->populate()) {
            $this->populate($column->name(), $callback);
        }

        if ($callback = $column->sort()) {
            $this->sort($column->name(), $callback);
        }
    }

    /**
     * Remove columns.
     *
     * @param array $keys
     * @return void
     */
    public function remove(array $keys): void
    {
        $this->remove = array_merge($this->remove, $keys);
    }

    /**
     * Set columns.
     *
     * @param array $keys
     * @return void
     */
    public function only(array $keys): void
    {
        $this->only = array_merge($this->only, $keys);
    }

    /**
     * Set the label for a column.
     *
     * @param string $key
     * @param string $label
     * @return void
     */
    public function label(string $key, string $label): void
    {
        $this->labels[$key] = $label;
    }

    /**
     * Set column position.
     *
     * @param string $key
     * @param string $direction
     * @param string $reference
     * @return void
     * @throws InvalidArgumentException
     */
    public function position(string $key, string $direction, string $reference): void
    {
        if (!in_array($direction, ['before', 'after'], true)) {
            throw new InvalidArgumentException("Invalid position direction '{$direction}'");
        }

        $this->positions[$key] = [$direction, $reference];
    }

    /**
     * Set column populate callback.
     *
     * @param string $key
     * @param callable $callback
     * @return void
     */
    public function populate(string $key, callable $callback): void
    {
        $this->populateCallbacks[$key] = $callback;
    }

    /**
     * Set sortable columns and sort callback.
     *
     * @param string $key
     * @param callable $callback
     * @return void
     */
    public function sort(string $key, callable $callback): void
    {
        $this->sortCallbacks[$key] = $callback;
    }

    /**
     * Get columns to add.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return $this->labels;
    }

    /**
     * Get removed columns.
     *
     * @return array
     */
    public function getRemoved(): array
    {
        return $this->remove;
    }

    /**
     * Get only columns.
     *
     * @return array
     */
    public function getOnly(): array
    {
        return $this->only;
    }

    /**
     * Get column positions.
     *
     * @return array
     */
    public function getPositions(): array
    {
        return $this->positions;
    }

    /**
     * Get a column populate callback.
     *
     * @param string $key
     * @return callable|null
     */
    public function getPopulateCallback(string $key): ?callable
    {
        return $this->populateCallbacks[$key] ?? null;
    }

    /**
     * Get sortable columns.
     *
     * @return array
     */
    public function getSortableColumns(): array
    {
        return array_combine(array_keys($this->sortCallbacks), array_keys($this->sortCallbacks));
    }

    /**
     * Get column sort callback.
     *
     * @param string $key
     * @return callable|null
     */
    public function getSortCallback(string $key): ?callable
    {
        return $this->sortCallbacks[$key] ?? null;
    }
}
