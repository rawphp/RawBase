# RawBase - Base Classes for PHP Applications 

[![Build Status](https://travis-ci.org/rawphp/RawBase.svg?branch=master)](https://travis-ci.org/rawphp/RawBase) [![Coverage Status](https://coveralls.io/repos/rawphp/RawBase/badge.png?branch=master)](https://coveralls.io/r/rawphp/RawBase?branch=master)
[![Latest Stable Version](https://poser.pugx.org/rawphp/raw-base/v/stable.svg)](https://packagist.org/packages/rawphp/raw-base) [![Total Downloads](https://poser.pugx.org/rawphp/raw-base/downloads.svg)](https://packagist.org/packages/rawphp/raw-base)
[![Latest Unstable Version](https://poser.pugx.org/rawphp/raw-base/v/unstable.svg)](https://packagist.org/packages/rawphp/raw-base) [![License](https://poser.pugx.org/rawphp/raw-base/license.svg)](https://packagist.org/packages/rawphp/raw-base)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fdc46b90-ef47-4fb5-a2a6-c48a599f4d0c/big.png)](https://insight.sensiolabs.com/projects/fdc46b90-ef47-4fb5-a2a6-c48a599f4d0c)

## Package Features
- The Component class offers a hook system that can be implemented by all subclasses.
- Static utility methods to dump arrays and objects for debugging
- Base Model class for other application models

## Installation

### Composer
RawBase is available via [Composer/Packagist](https://packagist.org/packages/rawphp/raw-base).

Add `"rawphp/raw-base": "1.0.0-RC"` to the require block in your composer.json and then run `composer install`.

```json
{
        "require": {
            "rawphp/raw-base": "1.0.0-RC"
        }
}
```

You can also simply run the following from the command line:

```sh
composer require rawphp/raw-base "1.0.0-RC"
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

#### 23-09-2014
- Added missing InvalidModelException and NullValueException classes.

#### 22-09-2014
- Added InitializationException and NotImplementedException classes.
- Moved exceptions into RawPHP\RawBase\Exceptions namespace.

#### 21-09-2014
- Tested on PHP 5.3

#### 18-09-2014
- Added debug ability to Component actions and filters - enable debug mode by passing debug => true to Component in `init()`.
- Moved RawException from RawPHP\RawBase\Exceptions to RawPHP\RawBase namespace.
- Moved Model from RawPHP\RawBase\Models to RawPHP\RawBase namespace.

#### 17-09-2014
- Added a `$log` member variable to Component class. This can host any logging class instance.

#### 15-09-2014
- Added .travis.yml for CI.
- Updated composer version alias.

#### 13-09-2014
- Removed init() call from Component constructor

#### 11-09-2014
- Initial Code Commit
