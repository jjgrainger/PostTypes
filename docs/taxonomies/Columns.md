## Columns

You can now modify a `Taxonomy` columns using the same methods as you would for a `PostType`. For example:

```php
// Create a taxonomy
$genres = new Taxonomy('genre');

// Add a column to the taxonomy admin table
$genres->columns()->add([
    'popularity' => __('Popularity')
]);

// Register the taxonomy to WordPress
$genres->register();
```
Please refer to the [PostTypes column documentation](../post-types/Columns.md) for more on how to work with the column manager.
