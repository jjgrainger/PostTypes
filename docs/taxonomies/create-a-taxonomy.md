# Taxonomies

Taxonomies are created using the `Taxonomy` class. This works identically to the `PostType` class and holds similar methods.

## Create a new taxonomy

Taxonomies are made by creating a new class that extends the `Taxonomy` abstract class. All Taxonomy classes require you to implement the `name()` method.

```php
use PostTypes\Taxonomy;

class Genres extends Taxonomy
{
    /**
     * Returns the taxonomy name to register to WordPress.
     *
     * @return string
     */
    public function name(): string
    {
        return 'genre';
    }
}
```

## Set the slug for the Taxonomy

By default, the Taxonomy name is used as the slug for the taxonomy too. To change this use the `slug()` method to return a slug string.

```php
use PostTypes\Taxonomy;

class Genres extends Taxonomy
{
    //...

    /**
     * Returns the taxonomy slug.
     *
     * @return string
     */
    public function slug(): string
    {
        return 'genres';
    }
}
```

## Register the Taxonomy to WordPress

Once your Taxonomy class is created it can be registered to WordPress by instantiating the class and calling the `register()` method in your plugin or theme.

```php
// Instantiate the Genres Taxonomy class.
$genres = new Genres;

// Register the Genres Taxonomy to WordPress.
$genres->register();
```

{% hint style="info" %}
The `register()` method hooks into WordPress and sets all the actions and filters required to create your taxonomy. You do not need to add any of your Taxonomy code in actions/filters. Doing so may lead to unexpected results.
{% endhint %}
