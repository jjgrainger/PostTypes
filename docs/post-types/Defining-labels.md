# Defining labels

Labels for a PostType are defined in the `labels()` method and should return an array of labels.

By default, an empty array is returned and the WordPress default labels are used.

See [`get_post_type_labels()`](https://developer.wordpress.org/reference/functions/get_post_type_labels/) for a full list of supported labels.

```php
use PostTypes\PostType;

class Books extends PostType
{
    //...

    /**
     * Returns the Books post type labels.
     *
     * @return array
     */
    public function labels(): array
    {
        return [
            'name'               => __( 'Books', 'my-text-domain' ),
            'singular_name'      => __( 'Book', 'my-text-domain' ),
            'menu_name'          => __( 'Books', 'my-text-domain' ),
            'all_items'          => __( 'Books', 'my-text-domain' ),
            'add_new'            => __( 'Add New', 'my-text-domain' ),
            'add_new_item'       => __( 'Add New Book', 'my-text-domain' ),
            'edit_item'          => __( 'Edit Book', 'my-text-domain' ),
            'new_item'           => __( 'New Book', 'my-text-domain' ),
            'view_item'          => __( 'View Book', 'my-text-domain' ),
            'search_items'       => __( 'Search Books', 'my-text-domain' ),
            'not_found'          => __( 'No Books found', 'my-text-domain' ),
            'not_found_in_trash' => __( 'No Books found in Trash', 'my-text-domain' ),
        ];
    }
}
```
