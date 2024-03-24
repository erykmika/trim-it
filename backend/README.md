# trim-it (backend)

A simple REST API written in PHP 8 without using any particular frameworks. It enables a user to shorten ("trim") a URL and generate its hash that can be used to retrieve the original URL. The application code was structured in such a way it follows the MVC architecture pattern. **The project is still in development**.

## Tech stack

|             Type             |                            Technology used                           |
| ---------------------------- |----------------------------------------------------------------------|
| Core scripting language      | PHP 8                                                                |
| Database (dev & test)        | MySQL                                                                |
| Unit testing                 | PHPUnit                                                              |
| Dependency management        | composer                                                             |

## Project setup

PHP >= 8.3 is recommended. An instance of active MySQL database with permissions to create/manage databases/users is needed.

### Clone the repository

```sh
git clone https://github.com/erykmika/trim-it.git
```

### Install dependencies (composer)

```sh
cd ./trim-it/
composer install
```
This assumes <a href="https://getcomposer.org/doc/00-intro.md#globally">the global installation of composer</a>.

### Configure the database

Modify **include/db_config.php** and, optionally, **tests/test_db_config.php** to provide database credentials for a development and a testing database respectively. Run the **create_schema.sql** script for both databases to create a proper schema for the application:

```sh
mysql -u <user> -p -d <database> < ./sql/create_schema.sql
```

### Run the application

You can use the Apache HTTP Server to handle HTTP requests. You will have to expose the **public** directory in the configuration. An **.htaccess** file specifying rewriting rules is provided. For the sake of simplicity, you can also use the in-built PHP web development server:

```sh
cd ./public/
php -S localhost:8080
```

Now the application will respond to the API requests on the specified addresss.

## Endpoints

The application handles and serves JSON-encoded requests/responses.

|   Method  |  URL          |   Description     |
|-----------|---------------|-------------------|
|   GET     | /url/:hash    | Get full URL      |
|   POST    | /hash         | Generate URL hash |

### POST JSON body structure

```json
{
    "url": "<url to be shortened>"
}

```

### Response JSON body structure
```json
{
    "status": "<success/failure>",
    "<hash/url>": "<hash/url>"
}
```

## Sample scripts for accessing the API

Python scripts that perform GET/POST requests to the application are located in the **sample-requests** directory.
To run it, make sure you have the **requests** Python package installed (globally). Apart from that, set the host address in
the **sample-requests/hostaddr.py** file to match your configuration.

```sh
python sample-requests/
```

This will start the **\_\_main\_\_.py** script.

## Unit testing

To launch tests, run the vendor-provided PHPUnit executable.

```sh
./vendor/bin/phpunit --testdox
```

The **--testdox** flag provides more user-friendly output of the tests.
