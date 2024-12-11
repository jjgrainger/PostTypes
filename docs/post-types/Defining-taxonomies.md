# Defining Taxonomies

Taxonomies for a PostType can be definied using the `taxonomies()` method. This method should return an array of taxonomy slugs to associate with the post type.

An empty array is returned by default and no taxonomies are attached to the PostType.

```php
use PostTypes\PostType;

class Books extends PostType
{
    //...

    /**
     * Returns taxonomies attached to the Books post type.
     *
     * @return array
     */
    public function taxonomies(): array
    {
        return [
            'category',
            'genre',
        ];
    }
}
```

This method only attaches the taxonomy to the post type, to _create_ a taxonomy see the [documentation](../taxonomies/Create-a-taxonomy.md) on creating a new taxonomy.

Taxonomies and post types can be created and registered in any order.
