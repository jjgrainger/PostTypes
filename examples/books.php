<?php

require __DIR__ . '/vendor/autoload.php';

use PostTypes\PostType;
use PostTypes\Taxonomy;

// Create a books Post Type
$books = new PostType( 'book' );

// Add the Genre Taxonomy
$books->taxonomy( 'genre' );

// Hide the date and author columns
$books->columns()->hide( [ 'date', 'author' ] );

// add a price and rating column
$books->columns()->add( [
    'rating' => __( 'Rating' ),
    'price' => __( 'Price' )
] );

// Populate the custom column
$books->columns()->populate( 'rating', function( $column, $post_id ) {
    echo get_post_meta( $post_id, 'rating' ) . '/10';
} );

// Populate the custom column
$books->columns()->populate( 'price', function( $column, $post_id ) {
    echo '&pound;' . get_post_meta( $post_id, 'price' );
} );

// Set sortable columns
$books->columns()->sortable( [
    'price' => [ 'price', true ],
    'rating' => [ 'rating', true ]
] );

// Set the Books menu icon
$books->icon( 'dashicons-book-alt' );

// Register the PostType to WordPress
$books->register();

// Create the genre Taxonomy
$genres = new Taxonomy( 'genre' );

// Add a popularity column to the genre taxonomy
$genres->columns()->add( [
    'popularity' => 'Popularity'
] );

// Populate the new column
$genres->columns()->populate( 'popularity', function( $content, $column, $term_id ) {
    return get_term_meta( $term_id, 'popularity', true );
} );

// Register the taxonomy to WordPress
$genres->register();
