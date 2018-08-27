# Filters

Set the taxonomy filters on the post type admin edit screen by passing an array to the `filters()` method

```php
$books->filters(['genres', 'category']);
```

The order of the filters are set by the order of the items in the array. An empty array will remove all dropdown filters.
