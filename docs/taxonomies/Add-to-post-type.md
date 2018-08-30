## Add to PostType

You can add a taxonomy to any post type by passing the post type name to the `posttype()` method.

```php
// Create the genre Taxonomy.
$genres = new Taxonomy('genre');

// Attach to the books post type
$genres->posttype('books');

// Register changes to WordPress.
$genres->register();
```

Alternatively, you can attach a taxonomy to a post type when creating a PostType using its [`taxonomy()`](../posttypes/Add-taxonomies.md) method.
