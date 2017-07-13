<?php

namespace PostTypes;

class Taxonomy
{
    /**
     * The names passed to the Taxonomy
     * @var mixed
     */
    public $names;

    /**
     * The Taxonomy name
     * @var string
     */
    public $name;

    /**
     * The singular label for the Taxonomy
     * @var string
     */
    public $singular;

    /**
     * The plural label for the Taxonomy
     * @var string
     */
    public $plural;

    /**
     * The Taxonomy slug
     * @var name
     */
    public $slug;

    /**
     * Create a Taxonomy
     * @param mixed $names The name(s) for the Taxonomy
     */
    public function __construct($names)
    {
        $this->names($names);
    }

    /**
     * Set the names for the Taxonomy
     * @param  mixed $names The name(s) for the Taxonomy
     * @return $this
     */
    public function names($names)
    {
        if (is_string($names)) {
            $names = ['name' => $names];
        }

        $this->names = $names;

        return $this;
    }
}
