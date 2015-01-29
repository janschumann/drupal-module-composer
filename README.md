Drush extension for installing and configuring drupal modules
=============================================================

## Overview

This is yet another approach to automate installation and configuring drupal modules similar to ```drush make```

A benefit is, that the configuration is done voa symfony dependency injection and therefor in a readable xml format.

## Installation

This project can be checked out with [composer](http://getcomposer.org).

```json
{
  "require": {
    "janschumann/drupal-module-composer": "*"
  }
}
```

As this is a drush extension, this must be copied to ```~/.drush``` directory. 

When this module is acceped on drupal.org, the installation will be done via ```bin/drush dl module-composer```.

## Confguration

Usually no configuration is necessary. As this module takes its configuration from the [drupal container](https://github.com/janschumann/drupal-dic).

## Usage

### Dump current module configuration

```sh
$ bin/drush mc-dump-config
```

### Install and configure modules

```sh
$ bin/drush mc-compose
```

