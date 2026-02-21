<?php

namespace PostTypes\Registration\Integrations;

use PostTypes\Contracts\PostTypeContract;

class ManagePostTypeFilters
{
    /**
     * PostType to register.
     *
     * @var PostTypeContract
     */
    protected $posttype;

    /**
     * Constructor.
     *
     * @param PostTypeContract $posttype
     */
    public function __construct(PostTypeContract $posttype)
    {
        $this->posttype = $posttype;
    }

    /**
     * Add hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('restrict_manage_posts', [$this, 'manageFilters'], 10, 1);
    }

    /**
     * Manage the post type filters.
     *
     * @param string $posttype
     * @return void
     */
    public function manageFilters(string $posttype): void
    {
        if ($posttype !== $this->posttype->name()) {
            return;
        }

        foreach ($this->posttype->filters() as $taxonomy) {
            if (!is_object_in_taxonomy($posttype, $taxonomy)) {
                continue;
            }

            $tax = get_taxonomy($taxonomy);

            if (! $tax) {
                return;
            }

            $query_var = $tax->query_var;
            $show_all = $tax->labels->all_items;
            $filter_by_item = $tax->labels->filter_by_item;
            $is_heirarchical = $tax->hierarchical;
            $selected = isset($_GET[$query_var]) ? $_GET[$query_var] : '';

            $options = [
                'name'            => $query_var,
                'value_field'     => 'slug',
                'taxonomy'        => $taxonomy,
                'show_option_all' => $show_all,
                'hierarchical'    => $is_heirarchical,
                'hide_empty'      => 0,
                'show_count'      => 0,
                'orderby'         => 'name',
                'selected'        => $selected,
            ];

            echo '<label class="screen-reader-text" for="' . $taxonomy . '">';
            echo $filter_by_item;
            echo '</label>';

            wp_dropdown_categories($options);
        }
    }
}
