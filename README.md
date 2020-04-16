# E-Leave Tool

## Table of Contents

- [Introduction](#markdown-header-introduction)
- [Requirements](#markdown-header-requirements)
- [Installation](#markdown-header-installation)
- [Usage](#markdown-header-usage)

## Introduction

This is the main repository that hosts E-Leave Leave Management Tool's source code. It can be deployed in __4__ containerized web services via Docker Engine, which are the following:

* [MySQL DB](https://www.mysql.com/) (port 3306)
* [Apache PHP Server](https://laravel.com/) (port 80)
* [MailHog](https://github.com/mailhog/MailHog) (port 8025)
* [phpMyAdmin](https://www.phpmyadmin.net/) (port 8080)

The project tree consists of the following base folders:

* `css`: Contains the basic `.css` styles that can be included in all pages.
* `database`: Contains a sample dump of the `eleave` MySQL database, and its EER diagram.
* `lib`: Contains a collection of PHP functions that are commonly used across all pages.
* `pages`: Contains all the pages that a user can navigate through the tool, written in PHP.
* `sample navigation`: Contains PNG images of a simple usage guide.
* `templates`: Contains HTML templates that can be used dynamically for leave request e-mails.

#### Sample Navigation



#### Database Schema

The `eleave` database complies with the following EER diagram:

![database](https://raw.githubusercontent.com/savvas-leoussis/e-leave/master/database/database.png)

## Requirements

- Docker Engine

> See [Get Docker Engine](https://docs.docker.com/install/linux/docker-ce/ubuntu/)

- docker-compose

> See [Install Docker Compose](https://docs.docker.com/compose/install/)

## Installation
Run the following commands:

    $ git clone https://github.com/savvas-leoussis/e-leave.git
    $ cd e-leave

## Usage

To get all the web service's Docker containers up, simply run the following command:

    $ docker-compose up -d

After a while, all services should be up, and you can simply hit the address `localhost:80` on your browser to access the main page.
Also, you can access phpMyAdmin via `localhost:8080`, with username `root` and password `root`.

To shutdown all services, simply run:

    $ docker-compose down

To pause all services, and maintain their state, run:

    $ docker-compose pause

To unpause:

    $ docker-compose unpause

To restart all services at once:

    $ docker-compose restart

> See [Compose command-line reference](https://docs.docker.com/compose/reference/) for more details.
