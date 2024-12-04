<?php

namespace PostTypes;

use PostTypes\Contracts\ColumnContract;

abstract class Column implements ColumnContract
{
    /**
     * Returns the column name.
     *
     * @return string
     */
    abstract public function name(): string;

    /**
     * Returns the column label.
     *
     * @return string
     */
    public function label(): string
    {
        return ucfirst(str_replace(['_', '-'], ' ', $this->name()));
    }

    /**
     * Populate the column.
     *
     * @param integer $objectId
     * @return void
     */
    public function populate(int $objectId): void
    {
        return;
    }

    /**
     * Set the column order.
     *
     * @return integer|null
     */
    public function order(): ?int
    {
        return null;
    }

    /**
     * Handle sorting the column.
     *
     * @param \WP_Query|\WP_Term_Query $query
     * @return void
     */
    public function sort($query)
    {
        return;
    }

    /**
     * Can the column be sorted.
     *
     * @return boolean
     */
    public function isSortable(): bool
    {
        return false;
    }

    /**
     * Check the current column name matches this column.
     *
     * @param string $name
     * @return boolean
     */
    public function isColumn(string $name): bool
    {
        return $name === $this->name();
    }
}
