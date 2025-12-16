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
     * Set the column position.
     *
     * @return array|null
     */
    public function position(): ?array;

    /**
     * Populate the column.
     *
     * @return callable|null
     */
    public function populate(): ?callable;

    /**
     * Handle sorting the column.
     *
     * @return callable|null
     */
    public function sort(): ?callable;
}
