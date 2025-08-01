<?php

use PostTypes\Taxonomy;
use PostTypes\Columns;

class Genres extends Taxonomy
{
    public function name(): string {
        return 'genre';
    }

    public function slug(): string {
        return 'genres';
    }

    public function posttypes(): array {
        return [
            'book',
        ];
    }

    public function columns( Columns $columns ): Columns {
        $columns->remove(['posts']);

        $columns->add(
            'popularity',
            __( 'Popularity', 'post-types' ),
            function( $term_id ) {
                echo get_term_meta( $term_id, 'popularity', true );
            }
        );

        $columns->order( [
            'popularity' => 2,
        ] );

        $columns->sortable( 'popularity', function( $query ) {
            $query->query_vars['orderby'] = 'meta_value';
            $query->query_vars['meta_key'] = 'popularity';
        } );

        return $columns;
    }

    public function labels(): array {
        return [
            'name'                       => __( 'Genres', 'post-types' ),
            'singular_name'              => __( 'Genre', 'post-types' ),
            'menu_name'                  => __( 'Genres', 'post-types' ),
            'all_items'                  => __( 'All Genres', 'post-types' ),
            'edit_item'                  => __( 'Edit Genre', 'post-types' ),
            'view_item'                  => __( 'View Genre', 'post-types' ),
            'update_item'                => __( 'Update Genre', 'post-types' ),
            'add_new_item'               => __( 'Add New Genre', 'post-types' ),
            'new_item_name'              => __( 'New Genre', 'post-types' ),
            'parent_item'                => __( 'Parent Genres', 'post-types' ),
            'parent_item_colon'          => __( 'Parent Genres: ', 'post-types' ),
            'search_items'               => __( 'Search Genres', 'post-types' ),
            'popular_items'              => __( 'Popular Genres', 'post-types' ),
            'separate_items_with_commas' => __( 'Seperate Genres with commas', 'post-types' ),
            'add_or_remove_items'        => __( 'Add or remove Genres', 'post-types' ),
            'choose_from_most_used'      => __( 'Choose from most used Genres', 'post-types' ),
            'not_found'                  => __( 'No Genres found', 'post-types' ),
        ];
    }
}
