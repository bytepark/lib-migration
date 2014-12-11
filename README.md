# Database migration library

## Status
[![alt text][2]][1]

Lightweight database migration library.

The lib-migration component offers an easy to use API for database migrations 
controlled from within a project or application, not differentiating between 
structural or data migrations. The migration workload itself is pure SQL and is
currently __not__ verified by the library. The correct syntax and integrity 
checks are the responsibility of the user.

## TODOS / Open Features

- Add SQL Schema abstraction for database repository / connection
- Add some error messaging/logging facility
- Rename Repository interface to Location?
- Rename Manager to something better!!!

## Concept

The library defines the public interfaces Connection, Repository and Lock. The 
instance of the Connection interface provides access to the database that is the 
subject of change, i.e. the database where one wants to migrate against. 
Implementations of the following databases are bundled in the component using PDO:
 
* Sqlite memory database 
* Sqlite file database
* MySQL database

The instance of the Repository interface represent a location where the single work
units are persisted. Therefore two repositories must be specified, one representing
the source of all the migrations and one representing the history of all already
migrated work units. Implementations of two filesystem, a database and a memory 
located repository are bundled.

When you want to persist in the filesystem, you can choose between a _flat_ and a 
_grouped_ directory structure. The work units are save in one directory in the first
case and a two subdirectory deep structure - depending on the unique ID of the work 
unit - in the latter case.

As the library is to be used in the context of an application, and as applications
are nowadays often parallelized, the Manager depends on a locking mechanism to 
ensure mutually exclusive execution of the database migrations not already present
in the history repository.

The four instances are injected during construction into the Manager class which
delegates the migration process.

## Installation

The recommended way to install lib-migration is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of lib-migration:

```bash
composer require bytepark/lib-migration
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

## Usage

### Example Filesystem to DB

The migration process scans a defined directory for files with the file extension
_.mig_. The set of files is then diffed against the already migrated set, which is
, in the example, persisted in a database table.

### Migration file requirements

The single migrations should be named in some sortable syntax to express
weak dependencies. Recommended is the naming of the files including a date, datetime
or timestamp prefix, i.e. 19700101-some-text.mig. The migrations will be processed
in the given order of the directory scan.

The content of the specific files is the pure SQL queries to execute against the 
database.

## Bridging Components

Will soon be linked here. Currently we know that the following bridges will be 
provided, each depending on this component:

* a Silex service provider
* a Redaxo4 CMS bridge
* a Symfony2 Bundle
* a symfony1 plugin
 
[1]: https://travis-ci.org/bytepark/lib-migration
[2]: https://api.travis-ci.org/bytepark/lib-migration.svg (build status)