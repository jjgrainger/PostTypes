# Menu Icons

WordPress has [Dashicons](https://developer.wordpress.org/resource/dashicons/), an icon font you can use with your custom post types.

To set the post type icon pass the dashicon icon slug in the `icon()` method.

```php
use PostTypes\PostType;

class Books extends PostType
{
    //...

    /**
     * Returns the admin menu icon for the Books post type.
     *
     * @return array
     */
    public function icon(): string
    {
        return 'dashicons-book-alt';
    }
}
```

A list of available icons can be found on the [WordPress documentation](https://developer.wordpress.org/resource/dashicons/)
