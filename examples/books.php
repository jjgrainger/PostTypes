<?php

require __DIR__ . '/vendor/autoload.php';

use PostTypes\PostType;

// Create a books Post Type
$books = new PostType('book');

// Add a Genre Taxonomy
$books->taxonomy('genre');

// Hide the date and author columns
$books->columns()->hide(['date', 'author']);

// add a price and rating column
$books->columns()->add([
    'rating' => __('Rating'),
    'price' => __('Price')
]);

// Populate the custom column
$books->columns()->populate('rating', function($column, $post_id) {
    echo get_post_meta($post_id, 'rating') . '/10';
});

// Populate the custom column
$books->columns()->populate('price', function($column, $post_id) {
    echo '&pound;' . get_post_meta($post_id, 'price');
});

// Set sortable columns
$books->columns()->sortable([
    'price' => ['price', true],
    'rating' => ['rating', true]
]);

// Set the Books menu icon
$books->icon('dashicons-book-alt');
