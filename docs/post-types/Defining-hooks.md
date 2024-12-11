# Defining hooks

Additional hooks are supported with the `hooks()` method.

Here you can register additional actions and filters to WordPress and allows you to keep logic associated with your post type in one class.

```php
use PostTypes\PostType;
use WP_Post;

class Books extends PostType
{
    //...

    /**
     * Adds additional hooks for the post type.
     *
     * @return array
     */
    public function hooks(): array
    {
        add_action( 'save_post_book', [ $this, 'onSave' ], 10, 3 );
    }

    /**
     * Run additional logic when saving a Books post.
     *
     * @param int $post_id
     * @param WP_Post $post
     * @param bool $update
     * @return void
     */
    public function onSave(int $post_id, WP_Post $post, bool $update)
    {
        // Run additional logic when a Books post type is saved...
    }
}
```
