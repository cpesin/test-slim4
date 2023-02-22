A simple project to test Slim 4 microframework...
===========

![GitHub](https://img.shields.io/github/license/cpesin/test-slim4)
![GitHub last commit](https://img.shields.io/github/last-commit/cpesin/test-slim4)
![Publish Docker image](https://github.com/cpesin/test-test-slim4/actions/workflows/build-docker-image.yml/badge.svg)

## Features

You can use this project to play with :
* Docker
* Slim 4
* Mysql / PhpMyAdmin
* Bootstrap

## Requirements

You need following sofwares to run this project : 
* Docker
* Makefile

## Installation

Clone the project :
`git clone https://github.com/cpesin/test-slim4.git`

Run containers :
`make up`

Install bundles :
`make install`

Create database and tables : 
`make schema_create`

Load fixtures :
`make load_fixtures`

Use `make bash` to enter in main container (server).

Stop docker's containers with `make stop`.

## Links

Website :
`http://localhost`

PHPMyAdmin : 
`http://localhost:8090`

Mailcatcher : 
`http://localhost:1080`

## Run tests

Run phpUnit's tests :
`make phpunit`

Run phpUnit's tests with code coverage :
`make coverage`

Code coverage is available at : `./coverage/index.html`

Run phpCs (dry-run) :
`make phpcs`

Run phpCs (fix automatically) :
`make phpcs_fix`

Run phpStan :
`make phpstan`