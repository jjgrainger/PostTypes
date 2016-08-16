<?php

$books = new PostType('book');

$books->taxonomy('genre');

$books->columns()->hide(['author', 'date']);

// just pass the slug
$books->columns()->add('price');
$books->columns()->position('price', 4);

// pass all options
$books->columns()->add([
    'slug' => 'price',
    'label' => __('Price'),
    'position' => 4
]);

// add column price -> slug, title, position
// $books->columns()->add('price', __('Price'), 4);

// populate column price
$books->columns()->populate('price', function($column, $post_id) {
    echo '£' . get_post_meta($post_id, 'price', true);
});

