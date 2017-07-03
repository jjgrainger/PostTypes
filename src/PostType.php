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
     * Create a PostType
     * @param mixed $names   A string for the name, or an array of names
     * @param array $options An array of options for the PostType
     */
    public function __construct($names, $options = [])
    {
        // assign names to the post type
        $this->names($names);

        // assign custom options to the post type
        $this->options($options);
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
}
