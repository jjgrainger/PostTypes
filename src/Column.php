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
     * @return callable|null
     */
    public function populate(): ?callable
    {
        return null;
    }

    /**
     * Set the column order.
     *
     * @return array|null
     */
    public function position(): ?array
    {
        return null;
    }

    /**
     * Return the sort callback for the column.
     *
     * @return callable|null
     */
    public function sort(): ?callable
    {
        return null;
    }

    /**
     * Return the before position array structure.
     *
     * @param string $reference
     * @return array
     */
    protected function before(string $reference): array
    {
        return ['before', $reference];
    }

    /**
     * Return the after position array structure.
     *
     * @param string $reference
     * @return array
     */
    protected function after(string $reference): array
    {
        return ['after', $reference];
    }
}
