# Notes

## Translations

Since 2.0 the `translation()` method has been removed. You can translate any labels and names when you assign them to the PostType or Taxonomy. It was removed to provide more control to the developer while encouraging best practices around internationalizing plugins and themes set out by [WordPress](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/).

```php
// Translating the post types plural and singular names.
$books = new PostType( [
    'name'     => 'book',
    'singular' => __( 'Book', 'YOUR_TEXTDOMAIN' ),
    'plural'   => __( 'Books', 'YOUR_TEXTDOMAIN' ),
    'slug'     => 'books',
] );

// Translating labels.
$books->labels( [
    'add_new_item' => __( 'Add new Book', 'YOUR_TEXTDOMAIN' ),
] );
```

## Custom Fields

The class has no methods for making custom fields for post types, use [Advanced Custom Fields](https://advancedcustomfields.com).

## Examples

The books example used in the README.md can be found in [examples/books.php](https://github.com/jjgrainger/posttypes/blob/master/examples/books.php).
