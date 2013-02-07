# Pagination for Symfony2 with Doctrine2 support
Symfony2 Pager with Doctrine2 support. Simple. Powerful. Without DI.

## Short example

    // in php
    $pager = new Pager($page, $itemsPerPage, $queryBuilder);

    # in twig template
    {% for item in pager %}
        {{ item.name }}
    {% endfor %}

## Features

1. Simple usage — creating Pager in one line without services
1. Pager implements Iterator interface — you can use `foreach` statement on it and easily switch to your own solution without breaking the templates
1. Doctrine2 EntityRepository can use PaginateTrait, that adds `paginate` & `paginateBy` methods

## List of supported data sources

1. array
1. Doctrine2 Collection
1. Doctrine2 QueryBuilder

## Installation

### Via composer (preffered)

Add dependency to your `composer.json` file:

    "require": {
        ...
        "dmishh/pagerbundle": "dev-master"
        ...
    }

Update your dependencies: `php composer.phar update`

Enable bundle in `AppKernel.php`:

    public function registerBundles()
    {
        $bundles = array(
        ...
            new Dmishh\Bundle\PagerBundle\DmishhPagerBundle(),
        ...
        );
    }

### Manually

Documentation in progress...

## Usage example

### array

    $array = array(1, 2, 3, 4, 5);

    $pager = new Pager(1, 3, $array);
    $pager->getItems(); // => (1, 2, 3)

    foreach ($pager as $item) {
        echo $item;
    } // 123

    $pager->setPage(2);
    $pager->getItems(); // => (4, 5)

### Doctrine2 QueryBuilder

    $queryBuilder = new QueryBuilder();
    $queryBulder
        ->select()
        ->from('MyEntity e')
        ->where('e.is_active = :is_active')
        ->bindParameter(':is_active', true);

    $pager = new Pager(1, 10, $queryBuilder);
    $pager->getItems(); // => returns array with 10 first items in result set (using limit & offset on DB level)

## TODO
* **Add distinct to queries**
* Use default route from request
* Move template filename to configuration
* Improve unit tests coverage
* Custom rendering logic — improve template set
* Add more adapters