jPDF is a PHP library which generates PDF files from UTF-8 encoded HTML.

It is forked from [mPDF](https://mpdf.github.io/) which is based on [FPDF](http://www.fpdf.org/) and [HTML2FPDF](http://html2fpdf.sourceforge.net/)
(see [CREDITS](CREDITS.txt)), with a number of enhancements. mPDF was written by Ian Back and is released
under the [GNU GPL v2 licence](LICENSE.txt). It is intended as a drop-in replacement for mPDF, just with code enhancements
and dropping support for PHP versions less than 7.4.

<!--
[![Latest Stable Version](https://poser.pugx.org/mpdf/mpdf/v/stable)](https://packagist.org/packages/mpdf/mpdf)
[![Total Downloads](https://poser.pugx.org/mpdf/mpdf/downloads)](https://packagist.org/packages/mpdf/mpdf)
[![License](https://poser.pugx.org/mpdf/mpdf/license)](https://packagist.org/packages/mpdf/mpdf)
-->

> ⚠ If you are viewing this file on jpdf GitHub repository homepage or on Packagist, please note that
> the default repository branch is `development` which can differ from the last stable release.

Requirements
============

PHP versions and extensions
---------------------------

- `PHP 7.4` is supported since `mPDF v8.0.4`
- `PHP 8.0` is supported since `mPDF v8.0.10`
- `jPDF` requires `PHP 7.4` and later

PHP `mbstring`, `gd`, and `bcmath` extensions have to be loaded.

Additional extensions may be required for some advanced features such as `zlib` for compression of output and
embedded resources such as fonts, or `xml` for character set conversion
and SVG handling.

Known server caveats
--------------------

jpdf has some problems with fetching external HTTP resources with single threaded servers such as `php -S`. A proper
server such as nginx (php-fpm) or Apache is recommended.

Support us
==========
<!--
Consider supporting development of jpdf with a donation of any value. [Donation button][1] can be found on the
[main page of the documentation][1].
-->
Installation
============

Official installation method is via composer and its packagist package [mpdf/mpdf](https://packagist.org/packages/mpdf/mpdf).

```
$ composer require jefhar/jpdf
```

### Replacing from mPDF
Simply change `mpdf\mpdf` to `jefhar/jpdf` in your `composer.json` file then run `composer update`.

If you are using PHP 8 and have used any named arguments in your mPDF calls, please
remove them. One of the points to jPDF is to make the code more maintainable and
readable, and variable and argument names is one of those changes.

Usage
=====

The simplest usage (since version 7.0) of the library would be as follows:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$jpdf = new \Mpdf\Mpdf();
$jpdf->WriteHTML('<h1>Hello world!</h1>');
$jpdf->Output();

```

This will output the PDF inline to the browser as `application/pdf` Content-type.

Setup & Configuration
=====================

All [configuration directives](https://mpdf.github.io/reference/mpdf-variables/overview.html) can
be set by the `$config` parameter of the constructor.

It is recommended to set one's own temporary directory via `tempDir` configuration variable.
The directory must have write permissions (mode `775` is recommended) for users using jPDF
(typically `cli`, `webserver`, `fpm`).

**Warning:** jPDF will clean up old temporary files in the temporary directory. Choose a path dedicated to jPDF only.


```php
<?php

$jpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/tmp']);

```

By default, the temporary directory will be inside vendor directory and will have write permissions from
`post_install` composer script.

For more information about custom temporary directory see the note on
[Folder for temporary files](https://mpdf.github.io/installation-setup/folders-for-temporary-files.html)
in the section on Installation & Setup in the [manual][1].

If you have problems, please read the section on
[troubleshooting](https://mpdf.github.io/troubleshooting/known-issues.html) in the manual.

Online manual
=============

Online manual is available at https://mpdf.github.io/.

General troubleshooting
=============

For general questions or troubleshooting please use [Discussions](https://github.com/mpdf/mpdf/discussions).

You can also use the [mpdf tag](https://stackoverflow.com/questions/tagged/mpdf) at Stack Overflow as the StackOverflow user base is more likely to answer you in a timely manner.

Contributing
============

Before submitting issues and pull requests please read the [CONTRIBUTING.md](https://github.com/mpdf/mpdf/blob/development/.github/CONTRIBUTING.md) file.

Unit Testing
============

Unit testing for jPDF is done using [PHPUnit](https://phpunit.de/).

To get started, run `composer install` from the command line while in the jPDF root directory
(you'll need [composer installed first](https://getcomposer.org/download/)).

To execute tests, run `composer test` from the command line while in the jPDF root directory.

Any assistance writing unit tests for jPDF is greatly appreciated. If you'd like to help, please
note that any PHP file located in the `/tests/` directory will be autoloaded when unit testing.

[1]: https://mpdf.github.io
