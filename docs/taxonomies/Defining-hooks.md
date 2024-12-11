# Defining hooks

Additional hooks are supported with the `hooks()` method.

Here you can register additional actions and filters to WordPress and allows you to keep logic associated with your post type in one class.

```php
use PostTypes\Taxonomy;

class Genres extends Taxonomy
{
    //...

    /**
     * Adds additional hooks for the post type.
     *
     * @return array
     */
    public function hooks(): array
    {
        add_action( 'saved_term', [ $this, 'onSave' ], 10, 5 );
    }

    /**
     * Run additional logic when saving a term.
     *
     * @param int $term_id
     * @param int $tt_id
     * @param string $taxonomy
     * @param bool $update
     * @param array $args
     * @return void
     */
    public function onSave(int $term_id, int $tt_id, string $taxonomy, bool $update, array $args)
    {
        // Check what taxonomy term we are working with...
        if ( $taxonomy !== $this->name() ) {
            return;
        }

        // Run additional logic when a term is saved...
    }
}
```
