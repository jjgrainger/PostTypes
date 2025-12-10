<?php

namespace PostTypes;

class ColumnBuilder
{
    /**
     * Columns instance.
     *
     * @var Columns
     */
    protected $manager;

    /**
     * Column key.
     *
     * @var string
     */
    protected $key;

    /**
     * Constructor.
     *
     * @param Columns $manager
     * @param string $key
     */
    public function __construct(Columns $manager, string $key)
    {
        $this->manager = $manager;
        $this->key = $key;
    }

    /**
     * Set the label for the column.
     *
     * @param string $label
     * @return ColumnBuilder
     */
    public function label(string $label): ColumnBuilder
    {
        $this->manager->add($this->key, $label);

        return $this;
    }

    /**
     * Position a column.
     *
     * @param string $direction
     * @param string $reference
     * @return ColumnBuilder
     */
    public function position(string $direction, string $reference): ColumnBuilder
    {
        $this->manager->position($this->key, $direction, $reference);

        return $this;
    }

    /**
     * Position a column after another.
     *
     * @param string $reference
     * @return ColumnBuilder
     */
    public function after(string $reference): ColumnBuilder
    {
        return $this->position('after', $reference);
    }

    /**
     * Position a column before another.
     *
     * @param string $reference
     * @return ColumnBuilder
     */
    public function before(string $reference): ColumnBuilder
    {
        return $this->position('before', $reference);
    }

    /**
     * Set columns populate callback.
     *
     * @param callable $callback
     * @return ColumnBuilder
     */
    public function populate(callable $callback): ColumnBuilder
    {
        $this->manager->populate($this->key, $callback);

        return $this;
    }

    /**
     * Set columns sort callback.
     *
     * @param callable $callback
     * @return ColumnBuilder
     */
    public function sort(callable $callback): ColumnBuilder
    {
        $this->manager->sort($this->key, $callback);

        return $this;
    }
}
