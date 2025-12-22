# Define filters

Filters that appear for the post type listing admin screen can be defined using the `filters()` method.

This must return an array of taxonomy slugs that are to be used as dropdown filters for the post type.

By default, an empty array is returned.

```php
use PostTypes\PostType;

class Books extends PostType
{
    //...

    /**
     * Returns the filters for the Books post type.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            'category',
        ];
    }
}
```
