<?php

namespace PostTypes;

/**
 * PostType
 *
 * Create WordPress custom post types easily
 *
 * @link http://github.com/jjgrainger/PostTypes/
 * @author  jjgrainger
 * @link    http://jjgrainger.co.uk
 * @version 2.0
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */
class PostType
{
    /**
     * Various names for the PostType
     * @var array
     */
    public $names;

    /**
     * Options for the PostType
     * @var array
     */
    public $options;

    /**
     * Labels for the PostType
     * @var array
     */
    public $labels;

    /**
     * Taxonomies for the PostType
     * @var array
     */
    public $taxonomies = [];

    /**
     * Create a PostType
     * @param mixed $names   A string for the name, or an array of names
     * @param array $options An array of options for the PostType
     */
    public function __construct($names, $options = [], $labels = [])
    {
        // assign names to the PostType
        $this->names($names);

        // assign custom options to the PostType
        $this->options($options);

        // assign labels to the PostType
        $this->labels($labels);
    }

    /**
     * Set the names for the PostType
     * @param  mixed $names A string for the name, or an array of names
     * @return $this
     */
    public function names($names)
    {
        // only the post type name is passed
        if (is_string($names)) {
            $names = ['name' => $names];
        }

        // set the names array
        $this->names = $names;

        return $this;
    }

    /**
     * Set the options for the PostType
     * @param  array $options An array of options for the PostType
     * @return $this
     */
    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Set the labels for the PostType
     * @param  array $labels An array of labels for the PostType
     * @return $this
     */
    public function labels(array $labels)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * Add a Taxonomy to the PostType
     * @param  string $taxonomy The Taxonomy name to add
     * @return $this
     */
    public function taxonomy($taxonomy)
    {
        $this->taxonomies[] = $taxonomy;

        return $this;
    }
}
