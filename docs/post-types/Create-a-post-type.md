# Create a Post Type

Post types can be made by creating a new class that extends the `PostType` abstract class. All PostType classes require you to implement the `name()` method. Below is an example of a simple Books PostType class to get started.

```php
use PostTypes\PostType;

class Books extends PostType
{
    /**
     * Returns the post type name to register to WordPress.
     *
     * @return string
     */
    public function name(): string
    {
        return 'book';
    }
}
```
## Register PostType to WordPress

Once your PostType class is created the new post type can be registered to WordPress by instantiating the class and calling the `register()` method in your plugin or theme.

```php
// Instantiate the Books PostType class.
$books = new Books;

// Register the books PostType to WordPress.
$books->register();
```

{% hint style="info" %}
The `register()` method hooks into WordPress and sets all the actions and filters required to create your custom post type. You do not need to add any of your PostTypes code in actions/filters. Doing so may lead to unexpected results.
{% endhint %}
