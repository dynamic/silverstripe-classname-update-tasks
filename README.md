# SilverStripe Classname Update Tasks

### Summary
This module allows for mappping legacy classnames to new classnames. This is most useful when migrating a website from SilverStripe 3 to SilverStripe 4 as classnames stored in the database are now FQN.

## Requirements

* SilverStripe CMS Recipe ^1.0

## Installation

`composer require dynamic/silverstripe-classname-update-tasks`

## Usage

#### For DataObjects
```php
<?php

namespace Foo\Bar\Baz;

use Dynamic\ClassNameUpdate\BuildTasks\DataObjectClassNameUpdate;
use Foo\Bar\Baz\MyDataObjectClass;

class MyDataObjectClassnameTask extends DataObjectClassNameUpdate
{
    public function __construct($key = [])
    {
        $key = [
            'MyDataObjectClass' => MyDataObjectClass::class,
        ];
        
        parent::__construct($key);
    }
}
```

#### For Pages
```php
<?php

namespace Foo\Bar\Baz;
use Dynamic\ClassNameUpdate\BuildTasks\SiteTreeClassNameUpdateTask;
use Foo\Bar\Baz\MyPageClass;

class MyPageClassnameTask extends SiteTreeClassNameUpdateTask
{
    public funcion __construct($key = [])
    {
        $key = [
            'MyPageClass' => MyPageClass::class,
        ];
        
        parent::__construct($key);
    }
}
```