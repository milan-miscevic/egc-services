# egc-services

[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat-square)](https://github.com/php-pds/skeleton)

This repository provides my solution for EGC Services' testing task.

## Installation

Run docker-compose to pull and build images and start containers.

```bash
docker-compose up -d
```

Run the database migration.

```bash
docker exec -it egc-services_php_1 bash
php bin/db.php
```

Fetch dependencies with Composer.

```bash
composer install
```

The project comes configured with my test domain (www.docker.mmm). Add this domain to the hosts file. This step is optional if there is a configured domain for the Docker.

## Notes

This project uses the Slim framework and the PHP-DI dependency injection container. I've not used them before this project and I used this project to play and experiment with them. Also, the Slim framework is advertised as a framework for generation of APIs.

This project has three layers:

* Actions (receiving of requests, packing of data, and sending of responses)
* Domain (business logic, entities, and validation)
* Persistence (work with the database)

Namespaces:

* `EgcServices\Api` - API actions
* `EgcServices\Army` - The domain and persistence for armies
* `EgcServices\Base` - Base classes for the rest of the project
* `EgcServices\Game` - The domain and persistence for games
* `EgcServices\Home` - The home page action
* `EgcServices\Simulator` - The simulator logic

## Composer commands

Unit tests and code coverage (the project is not fully covered):

```bash
composer test
composer coverage
```

Code fixing:

```bash
composer fix
```

Static analysis:

```bash
composer phpstan
composer psalm
```
