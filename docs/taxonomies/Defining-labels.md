# Defining labels

Labels for a Taxonomy are defined in the `labels()` method and should return an array of labels.

By default, an empty array is returned and the WordPress default labels are used.

See [`get_taxonomy_labels()`](https://developer.wordpress.org/reference/functions/get_taxonomy_labels/) for a full list of supported labels.

```php
use PostTypes\Taxonomy;

class Genres extends Taxonomy
{
    //...

    /**
     * Returns the Genres labels.
     *
     * @return array
     */
    public function labels(): array
    {
        return [
            'name'               => __( 'Genres', 'my-text-domain' ),
            'singular_name'      => __( 'Genre', 'my-text-domain' ),
            'search_items'       => __( 'Search Genres', 'my-text-domain' ),
            'all_items'          => __( 'Genres', 'my-text-domain' ),
            'edit_item'          => __( 'Edit Genre', 'my-text-domain' ),
            'view_item'          => __( 'View Genre', 'my-text-domain' ),
        ];
    }
}
```
