<?php

namespace PostTypes\Contracts;

interface ColumnContract
{
    /**
     * Returns the column name.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Returns the column label.
     *
     * @return string
     */
    public function label(): string;

    /**
     * Populate the column.
     *
     * @param integer $objectId
     * @return void
     */
    public function populate(int $objectId): void;

    /**
     * Set the column order.
     *
     * @return integer|null
     */
    public function order(): ?int;

    /**
     * Handle sorting the column.
     *
     * @param \WP_Query|\WP_Term_Query $query
     * @return void
     */
    public function sort($query);

    /**
     * Can the column be sorted.
     *
     * @return boolean
     */
    public function isSortable(): bool;
}
