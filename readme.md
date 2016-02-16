# Laravel 5 Cache

### Table of contents

[TOC]

## Usage

### Step 1: Add package to composer.json for autoloading

Use eilander/framework OR add the package to the main `composer.json` for autoloading and run `composer dump-autoload`, like so:

```
#!php
<?php
   "autoload": {
        "classmap": [
            "database"
        ],
        "psr-4": {
            "App\\": "app/",
            "Eilander\\Cache\\": "../library/eilander/cache/src/"
        }
    },
```


```
#!json

composer dump-autoload
```