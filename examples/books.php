<?php

use PostType\PostType;

// Create a books Post Type
$books = new PostType('book');

// Add a Genre Taxonomy
$books->taxonomy('genre');

// Hide the default Date column
$books->columns()->hide('date');

// Hide the default Author column
$books->columns()->hide('author');

// Hide multiple columns
$books->columns()->hide(['date', 'author']);

$books->columns()->add('rating')
                ->label('Rating')
                ->position(1);

// Add a Rating column
$books->columns()->add('rating', __('Rating'), 3);

// Populate the custom column
$books->columns()->populate('rating', function($column, $post_id) {
    echo get_post_meta($post_id, 'rating') . '/10';
});

// Add a Price column
$books->columns()->add('price', __('Price'));

// Populate the custom column
$books->columns()->populate('price', function($column, $post_id) {
    echo '&pound;' . get_post_meta($post_id, 'price');
});

// Set sortable columns
$books->sortable([
    'price' => ['price', true],
    'rating' => ['rating', true]
]);

// Set the Books menu icon
$books->icon('dashicons-book-alt');
