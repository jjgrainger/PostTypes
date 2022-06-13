<?php

namespace PostTypes;

use PostTypes\Columns;
use PostTypes\Registrars\PostTypeRegistrar;
use PostTypes\Contracts\PostType as PostTypeContract;

class PostType implements PostTypeContract
{
    /**
     * The names passed to the PostType.
     *
     * @var array
     */
    public $names;

    /**
     * The name for the PostType.
     *
     * @var string
     */
    public $name;

    /**
     * The singular for the PostType.
     *
     * @var string
     */
    public $singular;

    /**
     * The plural name for the PostType.
     *
     * @var string
     */
    public $plural;

    /**
     * The slug for the PostType.
     *
     * @var string
     */
    public $slug;

    /**
     * Options for the PostType.
     *
     * @var array
     */
    public $options;

    /**
     * Labels for the PostType.
     *
     * @var array
     */
    public $labels;

    /**
     * Taxonomies for the PostType.
     *
     * @var array
     */
    public $taxonomies = [];

    /**
     * Filters for the PostType.
     *
     * @var mixed
     */
    public $filters;

    /**
     * The menu icon for the PostType.
     *
     * @var string
     */
    public $icon;

    /**
     * The column manager for the PostType.
     *
     * @var mixed
     */
    public $columns;

    /**
     * Create a PostType.
     *
     * @param mixed $names   A string for the name, or an array of names.
     * @param array $options An array of options for the PostType.
     *
     * @return void
     */
    public function __construct($names, $options = [], $labels = [])
    {
        // Assign names to the PostType.
        $this->names($names);

        // Assign options to the PostType.
        $this->options($options);

        // Assign labels to the PostType.
        $this->labels($labels);
    }

    /**
     * Set the names for the PostType.
     *
     * @param  mixed $names A string for the name, or an array of names.
     *
     * @return $this
     */
    public function names($names)
    {
        // If only the post type name is passed.
        $this->names = is_string($names) ? ['name' => $names] : $names;

        return $this;
    }

    /**
     * Set the options for the PostType.
     *
     * @param  array $options An array of options for the PostType.
     *
     * @return $this
     */
    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Set the labels for the PostType.
     *
     * @param  array $labels An array of labels for the PostType.
     *
     * @return $this
     */
    public function labels(array $labels)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * Add a Taxonomy to the PostType.
     *
     * @param  mixed $taxonomies The Taxonomy name(s) to add.
     *
     * @return $this
     */
    public function taxonomy($taxonomies)
    {
        $this->taxonomies = is_string($taxonomies) ? [$taxonomies] : $taxonomies;

        return $this;
    }

    /**
     * Add filters to the PostType.
     *
     * @param  array $filters An array of Taxonomy filters.
     *
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
     * Flush rewrite rules.
     *
     * @link https://codex.wordpress.org/Function_Reference/flush_rewrite_rules
     *
     * @param  boolean $hard
     *
     * @return void
     */
    public function flush($hard = true)
    {
        flush_rewrite_rules($hard);
    }

    /**
     * Get the Column Manager for the PostType.
     *
     * @return PostTypes\Columns
     */
    public function columns()
    {
        if (!isset($this->columns)) {
            $this->columns = new Columns;
        }

        return $this->columns;
    }

    /**
     * Register the PostType to WordPress.
     *
     * @return void
     */
    public function register()
    {
        (new PostTypeRegistrar($this))->register();
    }
}
