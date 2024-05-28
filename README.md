## Installing Laravel Excel

#### Requirements

- PHP: ^7.2\|^8.0
- Laravel: ^5.8
- PhpSpreadsheet: ^1.21
- PHP extension php_zip enabled
- PHP extension php_xml enabled
- PHP extension php_gd2 enabled
- PHP extension php_iconv enabled
- PHP extension php_simplexml enabled
- PHP extension php_xmlreader enabled
- PHP extension php_zlib enabled

#### Php Extensions

Please ensure that you uncomment ```;extensions=gd``` from your php.ini file by removing the ;. 

In addition please ensure that the required extensions are installed and enabled by also uncommenting them out in the php.ini.

On a ubuntu based, installing dependencies is done by the following:

``` sudo apt install php-zip php-xml php8.1-gd php8.1-iconv php8.1-simplexml php8.1-xmlreader php-zip```

Installation generally follows: php-[package_name] or php[php_version_number]-[package_name]

For a windows machine, please see: https://www.php.net/manual/en/install.pecl.windows.php

## Installation

1. composer require phpoffice/phpspreadsheet
2. composer require psr/simple-cache ^2.0
3. composer require maatwebsite/excel:^3.1
4. composer update

The Excel facade should be auto detected. If not, you can manually add it as follows to the app.php in the config folder:

```providers' => [
    /*
     * Package Service Providers...
     */
    Maatwebsite\Excel\ExcelServiceProvider::class,
]```


```'aliases' => [
    ...
    'Excel' => Maatwebsite\Excel\Facades\Excel::class,
]```

See more: https://www.php.net/manual/en/install.pecl.windows.php