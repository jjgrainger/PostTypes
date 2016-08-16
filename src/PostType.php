<?php

namespace PostTypes;

/**
 * PostType
 *
 * Used to help manage a post types columns in the admin table
 *
 * @link http://github.com/jjgrainger/wp-custom-post-type-class/
 * @author  jjgrainger
 * @link    http://jjgrainger.co.uk
 * @version 1.4
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */
class PostType
{
    /**
     * The post type name.
     *
     * @var string
     */
    public $postTypeName;

    /**
     * The human friendly singular name.
     *
     * @var string
     */
    public $singular;

    /**
     * The human friendly plural name.
     *
     * @var string
     */
    public $plural;

    /**
     * The post type slug.
     *
     * @var string
     */
    public $slug;

    /**
     * The options passed for the post type.
     *
     * @var array
     */
    public $options;

    /**
     * An array of taxonomy names attached to the post type.
     *
     * @var array
     */
    public $taxonomies = [];

    /**
     * An array of new taxonomy objects.
     *
     * @var array
     */
    public $newTaxonomies = [];

    /**
     * An array of existing taxonomies attached to the class.
     *
     * @var array
     */
    public $existingTaxonomies = [];

    /**
     * An array of taxonomies to use for filters.
     *
     * @var array
     */
    public $filters = [];

    /**
     * The columns object for managing post type columns.
     *
     * @var PostTypes\Columns;
     */
    public $columns = false;

    /**
     * The textdomain to use for translation.
     *
     * @var string
     */
    public $textdomain = 'cpt';

    /**
     * Register a custom post type.
     *
     * @param mixed $names   The name(s) of the post type, accepts (post type name, slug, plural, singular).
     * @param array $options User submitted options.
     */
    public function __construct($names, $options = [])
    {
        // create necessary post type names
        $this->names($names);

        // set the options
        $this->options($options);

        // create a columns object
        $this->columns = new Columns();

        // add actions and filters
        $this->initialize();
    }

    /**
     * Set the post type names.
     *
     * @param mixed $names a post type name as string or an array of names
     */
    public function names($names)
    {

        // if a string is passed
        if (!is_array($names)) {
            $names = ['name' => $names];
        }

        // set the postTypeName
        $this->postTypeName = $names['name'];

        // an array of required names
        $required = [
            // 'name',
            'singular',
            'plural',
            'slug',
        ];

        foreach ($required as $key) {

            // if the name has not been passed, generate it
            if (!isset($names[$key])) {

                // if it is the singular/plural make the post type name human friendly
                if ($key === 'singular' || $key === 'plural') {
                    $name = ucwords(strtolower(str_replace(['-', '_'], ' ', $this->postTypeName)));

                    // if plural add an s
                    if ($key === 'plural') {
                        $name .= 's';
                    }

                // if the slug, slugify the post type name
                } elseif ($key === 'slug') {
                    $name = strtolower(str_replace([' ', '_'], '-', $this->postTypeName));
                }

            // otherwise use the name passed
            } else {
                $name = $names[$key];
            }

            // set the name
            $this->$key = $name;
        }
    }

    /**
     * Set the post type options.
     *
     * @param array $options an array of post type options
     */
    public function options($options)
    {

        // default labels.
        $labels = [
            'name' => sprintf(__('%s', $this->textdomain), $this->plural),
            'singular_name' => sprintf(__('%s', $this->textdomain), $this->singular),
            'menu_name' => sprintf(__('%s', $this->textdomain), $this->plural),
            'all_items' => sprintf(__('%s', $this->textdomain), $this->plural),
            'add_new' => __('Add New', $this->textdomain),
            'add_new_item' => sprintf(__('Add New %s', $this->textdomain), $this->singular),
            'edit_item' => sprintf(__('Edit %s', $this->textdomain), $this->singular),
            'new_item' => sprintf(__('New %s', $this->textdomain), $this->singular),
            'view_item' => sprintf(__('View %s', $this->textdomain), $this->singular),
            'search_items' => sprintf(__('Search %s', $this->textdomain), $this->plural),
            'not_found' => sprintf(__('No %s found', $this->textdomain), $this->plural),
            'not_found_in_trash' => sprintf(__('No %s found in Trash', $this->textdomain), $this->plural),
            'parent_item_colon' => sprintf(__('Parent %s:', $this->textdomain), $this->singular),
        ];

        // default options.
        $defaults = [
            'labels' => $labels,
            'public' => true,
            'rewrite' => [
                'slug' => $this->slug,
            ],
        ];

        // merge user submitted options with defaults.
        $this->options = array_replace_recursive($defaults, $options);
    }

    /**
     * Register a taxonomy to the Post Type.
     *
     * @see http://codex.wordpress.org/Function_Reference/register_taxonomy
     *
     * @param mixed $names   The names for the taxonomy.
     * @param array $options Taxonomy options.
     */
    public function taxonomy($names, $options = [])
    {
        // if only the name is passed, create an array
        if (!is_array($names)) {
            $names = ['name' => $names];
        }

        // add taxonomy to the list
        $this->taxonomies[] = $names['name'];

        // if taxonomy exists, just add the name
        if (taxonomy_exists($names['name'])) {
            $this->existingTaxonomies[] = $names['name'];

        // else create a new taxonomy
        } else {
            $this->newTaxonomies[$names['name']] = new Taxonomy($names, $options);
            $this->newTaxonomies[$names['name']]->textdomain($this->textdomain);
        }
    }

    /**
     * Set which filters appear on the admin table page for the post type.
     *
     * @param array $filters An array of taxonomy filters to display.
     */
    public function filters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * the columns object for the post type.
     *
     * @return PostType\Columns;
     */
    public function columns()
    {
        return $this->columns;
    }

    /**
     * Use this function to set the menu icon in the admin dashboard. Since WordPress v3.8
     * dashicons are used. For more information see @link http://melchoyce.github.io/dashicons/.
     *
     * @param string $icon dashicon name
     */
    public function icon($icon)
    {
        $this->options['menu_icon'] = $icon;
    }

    /**
     * Flush rewrite rules programatically.
     */
    public function flush()
    {
        flush_rewrite_rules();
    }

    /**
     * set the textdomain for the post type.
     *
     * @param string $textdomain Textdomain used for translation.
     */
    public function translation($textdomain)
    {
        $this->textdomain = $textdomain;
    }

    /**
     * bind methods to WordPress actions and filters.
     */
    public function initialize()
    {
        // register taxonomies.
        add_action('init', array(&$this, 'registerTaxonomies'));

        // register the post type.
        add_action('init', array(&$this, 'registerPostType'));

        // register existing taxonomies.
        add_action('init', array(&$this, 'registerExistingTaxonomies'));

        // add taxonomy to admin edit columns.
        add_filter('manage_edit-'.$this->postTypeName.'_columns', array(&$this, 'modifyColumns'));

        // populate the taxonomy columns with the posts terms.
        add_action('manage_'.$this->postTypeName.'_posts_custom_column', array(&$this, 'populateColumns'), 10, 2);

        // add filter select option to admin edit.
        add_action('restrict_manage_posts', array(&$this, 'modifyFilters'));

        // run filter to make columns sortable.
        add_filter('manage_edit-'.$this->postTypeName.'_sortable_columns', array(&$this, 'setSortableColumns'));

        // run action that sorts columns on request.
        add_action('load-edit.php', array(&$this, 'loadEdit'));

        // rewrite post update messages
        add_filter('post_updated_messages', array(&$this, 'modifyUpdatedMessages'));
        add_filter('bulk_post_updated_messages', array(&$this, 'modifyBulkUpdateMessages'), 10, 2);
    }

    /**
     * Register the post type.
     */
    public function registerPostType()
    {
        // check that the post type doesn't already exist.
        if (!post_type_exists($this->postTypeName)) {

            // register the post type.
            register_post_type($this->postTypeName, $this->options);
        }
    }

    /**
     * Register the post type taxonomies.
     */
    public function registerTaxonomies()
    {
        foreach ($this->newTaxonomies as $taxonomy_name => $tax) {
            // register the taxonomy with Wordpress
            register_taxonomy($taxonomy_name, $this->postTypeName, $tax->options);
        }
    }

    /**
     * Register existing taxonomies to the post type.
     */
    public function registerExistingTaxonomies()
    {
        foreach ($this->existingTaxonomies as $taxonomy_name) {
            register_taxonomy_for_object_type($taxonomy_name, $this->postTypeName);
        }
    }

    /**
     * Modify the post type admin filters.
     *
     * @param string $postType the current post type being viewed
     */
    public function modifyFilters($postType)
    {
        global $wp_query;

        $filters = [];

        // must set this to the post type you want the filter(s) displayed on.
        if ($postType == $this->postTypeName) {

            // if we have user supplied filters use them
            if (!empty($this->filters)) {
                $filters = $this->filters;

            // otherwise add taxonomies as fitlers
            } elseif (!empty($this->taxonomies)) {
                foreach ($this->taxonomies as $taxonomy) {
                    $filters[] = $taxonomy;
                }
            }

            // foreach of the taxonomies we want to create filters for
            foreach ($filters as $taxonomy_name) {

                // object for taxonomy, doesn't contain the terms
                $tax = get_taxonomy($taxonomy_name);

                // get taxonomy terms and order by name
                $args = [
                    'orderby' => 'name',
                    'hide_empty' => false,
                ];

                // get taxonomy terms
                $terms = get_terms($taxonomy_name, $args);

                // if we have terms
                if ($terms) {
                    // set up select box
                    printf(' &nbsp;<select name="%s" class="postform">', $taxonomy_name);

                    // default show all
                    printf('<option value="0">%s</option>', sprintf(__('Show all %s', $this->textdomain), $tax->label));

                    // foreach term create an option field
                    foreach ($terms as $term) {
                        // if filtered by this term make it selected
                        if (isset($_GET[$taxonomy_name]) && $_GET[$taxonomy_name] === $term->slug) {
                            printf('<option value="%s" selected="selected">%s (%s)</option>', $term->slug, $term->name, $term->count);
                        // create option for taxonomy
                        } else {
                            printf('<option value="%s">%s (%s)</option>', $term->slug, $term->name, $term->count);
                        }
                    }

                    // end the select field
                    echo '</select>&nbsp;';
                }
            }
        }
    }

    /**
     * Modify the post type columns with the columns object.
     *
     * @param array $columns an array of admin table columns
     * @return array an array of admin tbale columns
     */
    public function modifyColumns($columns)
    {
        // if the user has supplied a columns array use that
        if (!empty($this->columns()->items)) {
            return $this->columns()->items;
        }

        // otherwise add the taxonomies to the columns
        if (!empty($this->taxonomies)) {
            // determine what column the taxomies follow
            if ($this->postTypeName === 'post' || in_array('post_tag', $this->taxonomies)) {
                $after = 'tags';
            } elseif (in_array('categories', $this->taxonomies)) {
                $after = 'categories';
            } elseif (post_type_supports($this->postTypeName, 'author')) {
                $after = 'author';
            } else {
                $after = 'title';
            }

            // create a new columns array
            $newColumns = [];

            foreach ($columns as $key => $label) {
                $newColumns[$key] = $label;

                if ($key === $after) {
                    foreach ($this->taxonomies as $taxonomy) {
                        if ($taxonomy !== 'category' && $taxonomy !== 'post_tag') {
                            // get the taxonomy object for labels
                            $taxonomy_object = get_taxonomy($taxonomy);

                            // column key is the slug, value is friendly name
                            $newColumns[$taxonomy] = sprintf(__('%s', $this->textdomain), $taxonomy_object->labels->name);
                        }
                    }
                }
            }

            // set columns to new ones with taxonomies
            $columns = $newColumns;
        }

        // if user has made added custom columns
        foreach ($this->columns()->add as $key => $label) {

            // if user has assigned a custom position
            if (isset($this->columns()->positions[$key])) {
                $position = $this->columns()->positions[$key];

                $start = array_slice($columns, 0, $position, true);
                $end = array_slice($columns, $position, count($columns) - 1, true);

                $columns = $start + [$key => $label] + $end;
            } else {
                $columns[$key] = $label;
            }
        }

        // any columns the user has hidden
        foreach ($this->columns()->hide as $key) {
            unset($columns[$key]);
        }

        // overide with new columns
        return $columns;
    }

    /**
     * populate the columns for the admin table.
     *
     * @param string $column  the column name
     * @param int    $post_id the post id
     */
    public function populateColumns($column, $post_id)
    {
        // get wordpress $post object
        global $post;

        // use custom populate
        if (isset($this->columns()->populate[$column])) {
            call_user_func_array($this->columns()->populate[$column], [$column, $post_id]);

            return;
        }

        switch ($column) {
            // if column is a taxonomy associated with the post type
            case taxonomy_exists($column):
                // get the taxonomy for the post
                $terms = get_the_terms($post_id, $column);

                // if we have terms
                if (!empty($terms)) {
                    $output = [];

                    // loop through each term, linking to the 'edit posts' page for the specific term
                    foreach ($terms as $term) {

                        // output is an array of terms associated with the post
                        $output[] = sprintf(
                            '<a href="%s">%s</a>', // Define link format
                            esc_url(add_query_arg(['post_type' => $post->post_type, $column => $term->slug], 'edit.php')), // Create filter url
                            esc_html(sanitize_term_field('name', $term->name, $term->term_id, $column, 'display')) // Create friendly term name
                        );
                    }

                    // join the terms, separating them with a comma
                    echo implode(', ', $output);

                // if no terms found.
                } else {
                    // get the taxonomy object for labels
                    $taxonomy_object = get_taxonomy($column);

                    // echo no terms.
                    printf(__('No %s', $this->textdomain), $taxonomy_object->labels->name);
                }
                break;
            // if column is for the post ID
            case 'post_id':
                echo $post->ID;
                break;
            // if the column is prepended with 'meta_', this will automagically retrieve the meta values and display them
            case preg_match('/^meta_/', $column) ? true : false:
                // meta_book_author (meta key = book_author)
                $x = substr($column, 5);

                $meta = get_post_meta($post->ID, $x);

                echo implode(', ', $meta);
                break;
            // if the column is post thumbnail
            case 'icon':
                // create the edit link
                $link = esc_url(add_query_arg(['post' => $post->ID, 'action' => 'edit'], 'post.php'));

                // if it post has a featured image
                if (has_post_thumbnail()) {
                    // display post featured image with edit link
                    echo '<a href="'.$link.'">';
                    the_post_thumbnail(array(60, 60));
                    echo '</a>';
                } else {
                    // display default media image with link
                    echo '<a href="'.$link.'"><img src="'.site_url('/wp-includes/images/crystal/default.png').'" alt="'.$post->post_title.'" /></a>';
                }
                break;
        }
    }

    /**
     * Set the columns that are sortable.
     *
     * @param array $columns the sortable columns
     * @return array an array of sortable columns
     */
    public function setSortableColumns($columns)
    {
        if (!empty($this->columns()->sortable)) {
            // for each sortable column
            foreach ($this->columns()->sortable as $column => $values) {
                // make an array to merge into wordpress sortable columns
                $sortable_columns[$column] = $values[0];
            }
            // merge sortable columns array into wordpress sortable columns
            $columns = array_merge($sortable_columns, $columns);
        }

        return $columns;
    }

    /**
     * add filter to sort sortable columns.
     */
    public function loadEdit()
    {
        // Run filter to sort columns when requested
        add_filter('request', [&$this, 'sortColumns']);
    }

    /**
     * Sort data via the requested column.
     *
     * @param array $vars the request query vars
     * @return array the request query vars
     */
    public function sortColumns($vars)
    {
        // cycle through all sortable columns submitted by the user
        foreach ($this->columns()->sortable as $column => $values) {

            // retrieve the meta key from the user submitted array of sortable columns
            $meta_key = $values[0];

            // if the optional parameter is set and is set to true
            if (isset($values[1]) && true === $values[1]) {
                // vaules needed to be ordered by integer value
                $orderby = 'meta_value_num';
            } else {
                // values are to be order by string value
                $orderby = 'meta_value';
            }

            // check if we're viewing this post type
            if (isset($vars['post_type']) && $this->postTypeName == $vars['post_type']) {
                // find the meta key we want to order posts by
                if (isset($vars['orderby']) && $meta_key === $vars['orderby']) {
                    $add = [];

                    if (!taxonomy_exists($meta_key)) {
                        $add = [
                            'meta_key' => $meta_key,
                            'orderby' => $orderby,
                        ];
                    }

                    // merge the query vars with our custom variables
                    $vars = array_merge(
                        $vars,
                        $add
                    );
                }
            }
        }

        return $vars;
    }

    /**
     * Internal function that modifies the post type names in updated messages.
     *
     * @param array $messages an array of post updated messages
     *
     * @return array modified bulk updated messages
     */
    public function modifyUpdatedMessages($messages)
    {
        $post = get_post();
        $singular = $this->singular;

        $messages[$this->postTypeName] = [
            0 => '',
            1 => sprintf(__('%s updated.', $this->textdomain), $singular),
            2 => __('Custom field updated.', $this->textdomain),
            3 => __('Custom field deleted.', $this->textdomain),
            4 => sprintf(__('%s updated.', $this->textdomain), $singular),
            5 => isset($_GET['revision']) ? sprintf(__('%2$s restored to revision from %1$s', $this->textdomain), wp_post_revision_title((int) $_GET['revision'], false), $singular) : false,
            6 => sprintf(__('%s updated.', $this->textdomain), $singular),
            7 => sprintf(__('%s saved.', $this->textdomain), $singular),
            8 => sprintf(__('%s submitted.', $this->textdomain), $singular),
            9 => sprintf(
                __('%2$s scheduled for: <strong>%1$s</strong>.', $this->textdomain),
                date_i18n(__('M j, Y @ G:i', $this->textdomain), strtotime($post->post_date)),
                $singular
            ),
            10 => sprintf(__('%s draft updated.', $this->textdomain), $singular),
        ];

        return $messages;
    }

    /**
     * Internal function that modifies the post type names in bulk updated messages.
     *
     * @param array $messages an array of bulk updated messages
     *
     * @return array modified bulk updated messages
     */
    public function modifyBulkUpdateMessages($bulk_messages, $bulk_counts)
    {
        $singular = $this->singular;
        $plural = $this->plural;

        $bulk_messages[$this->postTypeName] = [
            'updated' => _n('%s '.$singular.' updated.', '%s '.$plural.' updated.', $bulk_counts['updated']),
            'locked' => _n('%s '.$singular.' not updated, somebody is editing it.', '%s '.$plural.' not updated, somebody is editing them.', $bulk_counts['locked']),
            'deleted' => _n('%s '.$singular.' permanently deleted.', '%s '.$plural.' permanently deleted.', $bulk_counts['deleted']),
            'trashed' => _n('%s '.$singular.' moved to the Trash.', '%s '.$plural.' moved to the Trash.', $bulk_counts['trashed']),
            'untrashed' => _n('%s '.$singular.' restored from the Trash.', '%s '.$plural.' restored from the Trash.', $bulk_counts['untrashed']),
        ];

        return $bulk_messages;
    }
}
