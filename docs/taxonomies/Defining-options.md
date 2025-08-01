# Defining options

Options for a Taxonomy are defined in the `options()` method and should return an array of valid [WordPress taxonomy options](https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters).

By default, an empty array is returned.

See [`register_taxonomy()`](https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters) for a full list of supported options.

```php
use PostTypes\Taxonomy;

class Genres extends Taxonomy
{
    //...

    /**
     * Returns the options for the Genres taxonomy.
     *
     * @return array
     */
    public function options(): array
    {
        return [
            'public'       => true,
            'hierarchical' => true,
        ];
    }
}
```
