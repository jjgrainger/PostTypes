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
     * Custom options for the Taxonomy
     * @var array
     */
    public $options;

    /**
     * Custom labels for the Taxonomy
     * @var array
     */
    public $labels;

    /**
     * Create a Taxonomy
     * @param mixed $names The name(s) for the Taxonomy
     */
    public function __construct($names, $options = [], $labels = [])
    {
        $this->names($names);

        $this->options($options);

        $this->labels($labels);
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

    /**
     * Set options for the Taxonomy
     * @param  array  $options
     * @return $this
     */
    public function options(array $options = [])
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Set the Taxonomy labels
     * @param  array  $labels
     * @return $this
     */
    public function labels(array $labels = [])
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * Register the Taxonomy to WordPress
     * @return void
     */
    public function register()
    {
        add_action('init', [&$this, 'registerTaxonomy'], 9);
    }

    public function registerTaxonomy()
    {
        if (!taxonomy_exists($this->name)) {
            // create names for the Taxonomy
            $this->createNames();

            // create options for the Taxonomy
            $options = $this->createOptions();

            // register the Taxonomy with WordPress
            register_taxonomy($this->name, null, $options);
        }
    }

    /**
     * Create names for the Taxonomy
     * @return void
     */
    public function createNames()
    {
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
                $name .= 's';
            }

            // asign the name to the PostType property
            $this->$key = $name;
        }
    }

    /**
     * Create options for Taxonomy
     * @return array Options to pass to register_taxonomy
     */
    public function createOptions()
    {
        // default options
        $options = [
            'hierarchical' => true,
            'rewrite' => [
                'slug' => $this->slug,
            ],
        ];

        // replace defaults with the options passed
        $options = array_replace_recursive($options, $this->options);

        // create and set labels
        if (!isset($options['labels'])) {
            $options['labels'] = $this->createLabels();
        }

        return $options;
    }

    /**
     * Create labels for the Taxonomy
     * @return array
     */
    public function createLabels()
    {
        // default labels
        $labels = [
            'name' => $this->plural,
            'singular_name' => $this->singular,
            'menu_name' => $this->plural,
            'all_items' => "All {$this->plural}",
            'edit_item' => "Edit {$this->singular}",
            'view_item' => "View {$this->singular}",
            'update_item' => "Update {$this->singular}",
            'add_new_item' => "Add New {$this->singular}",
            'new_item_name' => "New {$this->singular} Name",
            'parent_item' => "Parent {$this->plural}",
            'parent_item_colon' => "Parent {$this->plural}:",
            'search_items' => "Search {$this->plural}",
            'popular_items' => "Popular {$this->plural}",
            'separate_items_with_commas' => "Seperate {$this->plural} with commas",
            'add_or_remove_items' => "Add or remove {$this->plural}",
            'choose_from_most_used' => "Choose from most used {$this->plural}",
            'not_found' => "No {$this->plural} found",
        ];

        return array_replace($labels, $this->labels);
    }
}
