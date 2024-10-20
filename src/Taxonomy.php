<?php

namespace PostTypes;

use PostTypes\Columns;
use PostTypes\Registrars\TaxonomyRegistrar;
use PostTypes\Contracts\Taxonomy as TaxonomyContract;

/**
 * Taxonomy
 *
 * Create WordPress Taxonomies easily
 *
 * @link    https://github.com/jjgrainger/PostTypes/
 * @author  jjgrainger
 * @link    https://jjgrainger.co.uk
 * @version 2.2.1
 * @license https://opensource.org/licenses/mit-license.html MIT License
 */
class Taxonomy implements TaxonomyContract
{
    /**
     * The names passed to the Taxonomy.
     *
     * @var mixed
     */
    public $names;

    /**
     * The Taxonomy name.
     *
     * @var string
     */
    public $name;

    /**
     * The singular label for the Taxonomy.
     *
     * @var string
     */
    public $singular;

    /**
     * The plural label for the Taxonomy.
     *
     * @var string
     */
    public $plural;

    /**
     * The Taxonomy slug.
     *
     * @var string
     */
    public $slug;

    /**
     * Custom options for the Taxonomy.
     *
     * @var array
     */
    public $options;

    /**
     * Custom labels for the Taxonomy.
     *
     * @var array
     */
    public $labels;

    /**
     * Post types to register the Taxonomy to.
     *
     * @var array
     */
    public $posttypes = [];

    /**
     * The column manager for the Taxonomy.
     *
     * @var mixed
     */
    public $columns;

    /**
     * Create a Taxonomy.
     *
     * @param mixed $names   The name(s) for the Taxonomy.
     * @param array $options The Taxonomy options.
     * @param array $labels  The Taxonomy labels
     *
     * @return void
     */
    public function __construct($names, $options = [], $labels = [])
    {
        $this->names($names);

        $this->options($options);

        $this->labels($labels);
    }

    /**
     * Set the names for the Taxonomy.
     *
     * @param  mixed $names The name(s) for the Taxonomy.
     *
     * @return PostType\Taxonomy
     */
    public function names($names)
    {
        $this->names = is_string($names) ? ['name' => $names] : $names;

        return $this;
    }

    /**
     * Set options for the Taxonomy.
     *
     * @param  array $options An array of Taxonomy options.
     *
     * @return PostType\Taxonomy
     */
    public function options(array $options = [])
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Set the Taxonomy labels.
     *
     * @param  array  $labels The Taxonomy labels.
     *
     * @return PostType\Taxonomy
     */
    public function labels(array $labels = [])
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * Register post types to the taxonomy.
     *
     * @param  mixed $posttypes An array of post types names.
     *
     * @return PostType\Taxonomy
     */
    public function posttype($posttypes)
    {
        $this->posttypes = is_string($posttypes) ? [$posttypes] : $posttypes;

        return $this;
    }

    /**
     * Get the Column Manager for the Taxonomy.
     *
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
     * Register the Taxonomy to WordPress.
     *
     * @return void
     */
    public function register()
    {
        (new TaxonomyRegistrar($this))->register();
    }
}
