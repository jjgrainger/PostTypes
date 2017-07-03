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
     * Create a PostType
     * @param mixed  $names   String for the PostType name, or array for all names
     */
    public function __construct($names)
    {
        // assign names to the post type
        $this->names($names);
    }

    public function names($names)
    {
        // only the post type name is passed
        if (is_string($names)) {
            $names = ['name' => $names];
        }

        // set the names array
        $this->names = $names;
    }
}
