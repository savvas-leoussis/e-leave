# E-Leave Tool

## Table of Contents

- [Introduction](#introduction)
- [Example Navigation](#example-navigation)
- [Database Schema](#database-schema)
- [Requirements](#requirements)
- [Installation](#installation)

## Introduction

This is the main repository that hosts E-Leave Leave Management Tool's source code. It can be deployed in __4__ containerized web services via Docker Engine, which are the following:

* [MySQL DB](https://www.mysql.com/) (image: __mysql:8.0__, port: __3306__)
* [Apache PHP Server](https://laravel.com/) (image: __php:7.1.33-apache__, port: __80__)
* [MailHog](https://github.com/mailhog/MailHog) (image: __mailhog/mailhog:v1.0.0__, port: __8025__)
* [phpMyAdmin](https://www.phpmyadmin.net/) (image: __phpmyadmin/phpmyadmin__, port __8080__)

The project tree consists of the following base folders:

* `/css`: Contains the basic `.css` styles that can be included in all pages.
* `/database`: Contains a sample dump of the `eleave` MySQL database, and its EER diagram.
* `/img`: Contains image assets.
* `/lib`: Contains a collection of PHP functions that are commonly used across all pages.
* `/pages`: Contains all the pages that a user can navigate through the tool, written in PHP.
* `/sample navigation`: Contains PNG images of a simple usage guide.
* `/templates`: Contains HTML templates that can be used dynamically for leave request e-mails.

## Example Navigation

### Use Case 1 (Employee) - Submit a Request

> The employee logs into the tool on the URL: __http://localhost__ with his/her credentials provided by the company (Email: __employee@company.com__, Password: __password__).

![1](https://raw.githubusercontent.com/savvas-leoussis/e-leave/master/sample%20nagivation/1%20-%20E-Leave%20-%20Login.png)

> He/She enters the main tool dashboard.

![2](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/2%20-%20E-Leave%20-%20Dashboard%20-%20Empty.png?raw=true)

> The employee clicks the `Submit Request` button to create a new application, filling out all the fields.

![3](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/3%20-%20E-Leave%20-%20Submit%20Request.png?raw=true)

>The new application is added to the dashboard, with the `Pending` status.

![4](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/4%20-%20E-Leave%20-%20Dashboard.png?raw=true)

> Meanwhile, the corresponding supervisor receives an e-mail and either accepts or rejects the employee's request.

![5](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/5%20-%20MailHog.png?raw=true)

> The supervisor clicks either the `Accept` or the `Reject` button.

![6](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/6%20-%20Request%20accepted.png?raw=true)

> The employee receives an e-mail with information about the outcome of his/her request.

![7](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/7%20-%20MailHog%20-%20Accepted.png?raw=true)

> Going back to the dashboard, the status of the request is updated.

![8](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/8%20-%20E-Leave%20-%20Dashboard%20-%20Accepted.png?raw=true)

### Use Case 2 (Admin) - Create a User


## Database Schema

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
