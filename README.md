
# RawBase - Base Classes for PHP Applications

## Package Features

- Static utility methods to dump arrays and objects for debugging
- Base Model class for other application models

## Installation

### Composer
RawBase is available via [Composer/Packagist](https://packagist.org/packages/rawphp/raw-base).

Add `"rawphp/raw-base": "dev-master"` to the require block in your composer.json and then run `composer install`.

```json
{
        "require": {
            "rawphp/raw-base": "dev-master"
        }
}
```

You can also simply run the following from the command line:

```sh
composer require rawphp/raw-base "dev-master"
```

### Tarball
Alternatively, just copy the contents of the RawBase folder into somewhere that's in your PHP `include_path` setting. If you don't speak git or just want a tarball, click the 'zip' button at the top of the page in GitHub.

## Basic Usage

```php
<?php

use RawPHP\RawBase\Component;

class Service extends Component { }

// dump array in formatted fashion
Service::arrayDump( array( 'key' => 'value' ) );

// dump object in formatted fashion
Component::objectDump( new Service( ) );
```

## License
This package is licensed under the [MIT](https://github.com/rawphp/RawBase/blob/master/LICENSE). Read LICENSE for information on the software availability and distribution.

## Contributing

Please submit bug reports, suggestions and pull requests to the [GitHub issue tracker](https://github.com/rawphp/RawBase/issues).

## Changelog

#### 11-09-2014
- Initial Code Commit
