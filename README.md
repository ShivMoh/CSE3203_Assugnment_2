## Installing Laravel Excel

#### Requirements

- PHP: ^7.2\|^8.0
- Laravel: ^5.8
- PhpSpreadsheet: ^1.21
- PHP extensions:
    - zip 
    - xml 
    - gd2 
    - iconv 
    - simplexml 
    - xmlreader 
    - zlib 

#### Php Extensions Setup

Ensure the required PHP extensions are installed and enabled. Uncomment the necessary extensions in your `php.ini` file. For example, uncomment `;extension=gd` by removing the `;`.

##### Ubuntu Installation
Run the following command to install the necessary extensions on an Ubuntu-based system:
``` sudo apt install php-zip php-xml php8.1-gd php8.1-iconv php8.1-simplexml php8.1-xmlreader php-zip```
Installation generally follows: php-[package_name] or php[php_version_number]-[package_name]

##### Windows Installation
Refer to the [official PHP documentation](https://www.php.net/manual/en/install.pecl.windows.php) for instructions on installing extensions on a Windows machine.

## Installation

 1. Require the necessary packages:
```
composer require phpoffice/phpspreadsheet
composer require psr/simple-cache ^2.0
composer require maatwebsite/excel:^3.1
composer update
```

2. The Excel facade should be auto detected. If not, manually add it to `app.php` in the `config` folder:

```
providers' => [
    /*
     * Package Service Providers...
     */
    Maatwebsite\Excel\ExcelServiceProvider::class,
]
```


```
'aliases' => [
    ...
    'Excel' => Maatwebsite\Excel\Facades\Excel::class,
]
```

For more details, visit the [official PHP documentation](https://www.php.net/manual/en/install.pecl.windows.php).
