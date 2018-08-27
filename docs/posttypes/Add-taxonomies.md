# Add Taxonomies

New and existing Taxonomies can be added to a PostType using its `taxonomy()` method by passing the taxonomy name.

```php
// Create a books post type
$books = new PostType('book');

// Add the genre taxonomy to the book post type
$books->taxonomy('genre');

// Register the post type to WordPress
$books->register();
```
See the [documentation](#) on creating a new Taxonomy. Taxonomies and PostTypes can be created in any order.
