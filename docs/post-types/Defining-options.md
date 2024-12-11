# Defining options

Options for a PostType are defined in the `options()` method and should return an array of valid [WordPress post type options](https://developer.wordpress.org/reference/functions/register_post_type/#parameters).

By default, an empty array is returned but these options are merged with a generated options array in [PostTypes](#) and whatever options are defined here will overwrite those defaults.

See [`register_post_type()`](https://developer.wordpress.org/reference/functions/register_post_type/#parameters) for a full list of supported options.

```php
use PostTypes\PostType;

class Books extends PostType
{
    //...

    /**
     * Returns the options for the Books post type.
     *
     * @return array
     */
    public function options(): array
    {
        return [
            'public'       => true,
            'show_in_rest' => true,
        ];
    }
}
```
