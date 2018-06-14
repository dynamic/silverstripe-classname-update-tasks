# SilverStripe Classname Update Tasks

### Summary
This module allows for mappping legacy classnames to new classnames. This is most useful when migrating a website from SilverStripe 3 to SilverStripe 4 as classnames stored in the database are now FQN.

## Requirements

* SilverStripe CMS Recipe ^1.0

## Installation

`composer require-dev dynamic/silverstripe-classname-update-tasks`

## Usage

- Upgrade your codebase with the [SilverStripe Upgrader Tool](https://packagist.org/packages/silverstripe/upgrader).
- Import your database into your upgraded SilverStripe 4 website.
- Set the config for your `.upgrade.yml` absolute path for the task:

```yml
Dynamic\ClassNameUpdate\BuildTasks\DatabaseClassNameUpdateTask:
  upgrade_file_path: "/abs/path/to/.upgrade.yml"
```

- Run the "Database ClassName Update Task" from cli or the browser.
- Be sure to check if there are any ClassNames that didnot update properly.