## Define feature supports

You can define the features your post types supports using the `supports` method. This works similarly to the [`post_type_supports`](https://developer.wordpress.org/reference/functions/post_type_supports/) function in WordPress and returns an array of 'features'.

The `title` and `editor` features are enabled by default, matching the WordPress defaults. A list of available features can be seen in the [WordPress documentation](https://developer.wordpress.org/reference/functions/post_type_supports/#more-information).

```php
use PostTypes\PostType;

class Books extends PostType
{
    //...

    /**
     * Returns features the Books post type supports.
     *
     * @return array
     */
    public function supports(): array
    {
        return [
            'title',
            'editor',
            'custom-fields',
        ];
    }
}
```
