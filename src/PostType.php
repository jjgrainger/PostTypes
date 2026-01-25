<?php

namespace PostTypes;

use PostTypes\Columns;
use PostTypes\Registrars\PostTypeRegistrar;

/**
 * PostType
 *
 * Create WordPress custom post types easily
 *
 * @link    https://github.com/jjgrainger/PostTypes/
 * @author  jjgrainger
 * @link    https://jjgrainger.co.uk
 * @version 2.2.2
 * @license https://opensource.org/licenses/mit-license.html MIT License
 */
class PostType
{
    /**
     * The names passed to the PostType
     * @var array
     */
    public $names;

    /**
     * The name for the PostType
     * @var string
     */
    public $name;

    /**
     * The singular for the PostType
     * @var string
     */
    public $singular;

    /**
     * The plural name for the PostType
     * @var string
     */
    public $plural;

    /**
     * The slug for the PostType
     * @var string
     */
    public $slug;

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
     * Filters for the PostType
     * @var mixed
     */
    public $filters;

    /**
     * The menu icon for the PostType
     * @var string
     */
    public $icon;

    /**
     * The column manager for the PostType
     * @var mixed
     */
    public $columns;

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

        // create names for the PostType
        $this->createNames();

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
     * @param  mixed $taxonomies The Taxonomy name(s) to add
     * @return $this
     */
    public function taxonomy($taxonomies)
    {
        $taxonomies = is_string($taxonomies) ? [$taxonomies] : $taxonomies;

        foreach ($taxonomies as $taxonomy) {
            $this->taxonomies[] = $taxonomy;
        }

        return $this;
    }

    /**
     * Add filters to the PostType
     * @param  array $filters An array of Taxonomy filters
     * @return $this
     */
    public function filters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Set the menu icon for the PostType
     * @param  string $icon A dashicon class for the menu icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Flush rewrite rules
     * @link https://codex.wordpress.org/Function_Reference/flush_rewrite_rules
     * @param  boolean $hard
     * @return void
     */
    public function flush($hard = true)
    {
        flush_rewrite_rules($hard);
    }

    /**
     * Get the Column Manager for the PostType
     * @return Columns
     */
    public function columns()
    {
        if (!isset($this->columns)) {
            $this->columns = new Columns;
        }

        return $this->columns;
    }

    /**
     * Register the PostType to WordPress
     * @return void
     */
    public function register()
    {
        (new PostTypeRegistrar($this))->register();
    }

    /**
     * Create the required names for the PostType
     * @return void
     */
    public function createNames()
    {
        // names required for the PostType
        $required = [
            'name',
            'singular',
            'plural',
            'slug',
        ];

        foreach ($required as $key) {
            // if the name is set, assign it
            if (isset($this->names[$key])) {
                $this->$key = $this->names[$key];
                continue;
            }

            // if the key is not set and is singular or plural
            if (in_array($key, ['singular', 'plural'])) {
                // create a human friendly name
                $name = ucwords(strtolower(str_replace(['-', '_'], ' ', $this->names['name'])));
            }

            if ($key === 'slug') {
                // create a slug friendly name
                $name = strtolower(str_replace([' ', '_'], '-', $this->names['name']));
            }

            // if is plural or slug, append an 's'
            if (in_array($key, ['plural', 'slug'])) {
                if (substr($name, strlen($name) - 1, 1) == "y") {
                    $name = substr($name, 0, strlen($name) - 1) . "ies";
                } else {
                    $name .= 's';
                }
            }

            // asign the name to the PostType property
            $this->$key = $name;
        }
    }

    /**
     * Create options for PostType
     * @return array Options to pass to register_post_type
     */
    public function createOptions()
    {
        // default options
        $options = [
            'public' => true,
            'rewrite' => [
                'slug' => $this->slug
            ]
        ];

        // replace defaults with the options passed
        $options = array_replace_recursive($options, $this->options);

        // create and set labels
        if (!isset($options['labels'])) {
            $options['labels'] = $this->createLabels();
        }

        // set the menu icon
        if (!isset($options['menu_icon']) && isset($this->icon)) {
            $options['menu_icon'] = $this->icon;
        }

        return $options;
    }

    /**
     * Create the labels for the PostType
     * @return array
     */
    public function createLabels()
    {
        // default labels
        $labels = [
            'name' => $this->plural,
            'singular_name' => $this->singular,
            'menu_name' => $this->plural,
            'all_items' => $this->plural,
            'add_new' => "Add New",
            'add_new_item' => "Add New {$this->singular}",
            'edit_item' => "Edit {$this->singular}",
            'new_item' => "New {$this->singular}",
            'view_item' => "View {$this->singular}",
            'search_items' => "Search {$this->plural}",
            'not_found' => "No {$this->plural} found",
            'not_found_in_trash' => "No {$this->plural} found in Trash",
            'parent_item_colon' => "Parent {$this->singular}:",
        ];

        return array_replace_recursive($labels, $this->labels);
    }

    /**
     * Calculate the filters for the PostType
     * @return array
     */
    public function getFilters()
    {
        // default filters are empty
        $filters = [];

        // if custom filters have been set, use them
        if (!is_null($this->filters)) {
            return $this->filters;
        }

        // if no custom filters have been set, and there are
        // Taxonomies assigned to the PostType
        if (is_null($this->filters) && !empty($this->taxonomies)) {
            // create filters for each taxonomy assigned to the PostType
            return $this->taxonomies;
        }

        return $filters;
    }
}
