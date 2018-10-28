## Add to post type

You can add a taxonomy to any post type by passing the post type name to the `posttype()` method.

```php
// Create the genre taxonomy.
$genres = new Taxonomy( 'genre' );

// Attach to the book post type.
$genres->posttype( 'books' );

// Register changes to WordPress.
$genres->register();
```

Alternatively, you can attach a taxonomy to a post type when creating a post type using its [`taxonomy()`](../post-types/Add-taxonomies.md) method.
