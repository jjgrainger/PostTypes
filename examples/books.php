<?php

use PostTypes\PostType;
use PostTypes\Columns;

class Books extends PostType {

    public function name(): string {
        return 'book';
    }

    public function slug(): string {
        return 'books';
    }

    public function labels(): array {
        return [
            'name'               => __( 'Book', 'post-types' ),
            'singular_name'      => __( 'Book', 'post-types' ),
            'menu_name'          => __( 'Books', 'post-types' ),
            'all_items'          => __( 'Books', 'post-types' ),
            'add_new'            => __( 'Add New', 'post-types' ),
            'add_new_item'       => __( 'Add New Book', 'post-types' ),
            'edit_item'          => __( 'Edit Book', 'post-types' ),
            'new_item'           => __( 'New Book', 'post-types' ),
            'view_item'          => __( 'View Book', 'post-types' ),
            'search_items'       => __( 'Search Books', 'post-types' ),
            'not_found'          => __( 'No Books found', 'post-types' ),
            'not_found_in_trash' => __( 'No Books found in Trash', 'post-types'),
            'parent_item_colon'  => __( 'Parent Book', 'post-types' ),
        ];
    }

    public function taxonomies(): array {
        return [
            'post_tag',
            'genre',
        ];
    }

    public function supports(): array {
        return [
            'title',
            'editor',
            'author',
            'custom-fields',
        ];
    }

    public function options(): array {
        return [
            'show_in_rest' => false,
        ];
    }

    public function icon(): string {
        return 'dashicons-book';
    }

    public function filters(): array {
        return [
            'genre',
            'post_tag',
        ];
    }

    public function columns( Columns $columns ): Columns {

        $columns->remove( [ 'author', 'date' ] );

        $columns->column( new Price );

        $columns->add( 'rating', __( 'Rating', 'post-types' ) );

        $columns->populate( 'rating', function( $post_id ) {
            echo get_post_meta( $post_id, 'rating', true );
        } );

        $columns->sortable( 'rating', function( $query ) {
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', 'rating');
        } );

        $columns->order( [
            'price'          => 4,
            'rating'         => 5,
            'taxonomy-genre' => 2,
            'tags'           => 3,
        ] );

        return $columns;
    }
}
