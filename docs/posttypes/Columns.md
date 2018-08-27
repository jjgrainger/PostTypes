# Columns

You can now modify a PostTypes admin columns using the `column()` manager.

#### Adding Columns

You can add columns to the admin edit screen by passing an array of slugs and labels to the `add()` method.

```php
// add multiple columns and set their labels
$books->columns()->add([
    'rating' => __('Rating'),
    'price' => __('Price')
]);
```

#### Hiding Columns

You can hide columns by passing the column slug to the `hide()` method. You can hide multiple columns by passing an array of column slugs.

```php
$books->columns()->hide('author');

$books->columns()->hide(['author', 'date']);
```

#### Set Columns

You can force set all the columns to display on the admin page with the `set()` method by passing an array of the column slugs and labels.

```php
$books->columns()->set([
    'cb' => '<input type="checkbox" />',
    'title' => __("Title"),
    'genre' => __("Genres"),
    'rating' => __("Rating"),
    'date' => __("Date")
]);
```

#### Column Order

After hiding and adding columns you may want to rearrange the column order. To do this use the `order()` method. You only have to pass through an array of the columns you want to reposition, not all of them. Their positions are based on a zero based index.

```php
$books->columns()->order([
    'rating' => 2,
    'genre' => 4
]);
```

#### Populating Columns

You can populate any column using the `populate()` method and passing the column slug and function.

```php
$books->columns()->populate('rating', function($column, $post_id) {
    echo get_post_meta($post_id, 'rating', true) . '/10';
});
```

#### Sorting Columns

You can choose which custom columns are sortable with the `sortable()` method. This method accepts an array of column slugs and an array of sorting options.

The first option is the `meta_key` to sort the colums by.

The second option is whether to order the items numerically (`true`) or alphabetically (`false`) by default.

```php
// will make both the price and rating columns sortable and ordered numerically
$books->columns()->sortable([
    'price' => ['price', true],
    'rating' => ['rating', true]
]);
```
