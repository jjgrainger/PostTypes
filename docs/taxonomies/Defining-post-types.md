# Defining Post Types

Post types for a Taxonomy can be definied using the `posttypes()` method. This method should return an array of post type names to associate with the taxonomy.

An empty array is returned by default and no post types are attached to the Taxonomy.

```php
use PostTypes\Taxonomy;

class Genres extends Taxonomy
{
    //...

    /**
     * Returns post types attached to the Genres taxonomy.
     *
     * @return array
     */
    public function posttypes(): array
    {
        return [
            'post',
            'books',
        ];
    }
}
```

This method only attaches the post type to the taxonomy, to _create_ a post type see the [documentation](../post-types/Create-a-post-type.md) on creating a new post type.

Taxonomies and post types can be created and registered in any order.
