# Add Taxonomies

You can add new and existing taxonomies to a post type by passing the taxonomy name to the `taxonomy()` method.

```php
// Create a book post type.
$books = new PostType( 'book' );

// Add the genre taxonomy to the book post type.
$books->taxonomy( 'genre' );

// Add the default category taxonomy.
$books->taxonomy( 'category' );

// Register the post type to WordPress.
$books->register();
```

This method only attaches the taxonomy to the post type, to _create_ a taxonomy see the [documentation](../taxonomies/Create-a-taxonomy.md) on creating a new taxonomy.

Taxonomies and post types can be created in any order.
