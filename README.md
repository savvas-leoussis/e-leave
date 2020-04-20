# E-Leave Tool

## Table of Contents

* [Introduction](#introduction)
    * [Quick Summary](#quick-summary)
    * [Containers](#containers)
* [Requirements](#requirements)
* [Installation](#installation)
* [Example Navigation](#example-navigation)
    * [Use Case 1 (Employee) - Submit a Request](#use-case-1-employee---submit-a-request)
    * [Use Case 2 (Admin) - Create a User](#use-case-2-admin---create-a-user)
    * [Use Case 3 (Admin) - Edit a User](#use-case-3-admin---edit-a-user)
* [Database Schema](#database-schema)

## Introduction

### Quick Summary

E-Leave is a leave management software that provides to employees of a company the ability to create leave requests to their perspective managers/supervisors. It also allows administrators to process those applications and oversee the incoming leave requests.

### Containers

This is the main repository that hosts E-Leave Leave Management Tool's source code. It can be deployed in __4__ containerized web services via Docker Engine, which are the following:

* [MySQL DB](https://www.mysql.com/) (image: __mysql:8.0__, port: __3306__)
* [Apache PHP Server](https://laravel.com/) (image: __php:7.1.33-apache__, port: __80__)
* [MailHog](https://github.com/mailhog/MailHog) (image: __mailhog/mailhog:v1.0.0__, port: __8025__)
* [phpMyAdmin](https://www.phpmyadmin.net/) (image: __phpmyadmin/phpmyadmin__, port __8080__)

### Project Tree

The project tree consists of the following base folders:

* `/css`: Contains the basic `.css` styles that can be included in all pages.
* `/database`: Contains a sample dump of the `eleave` MySQL database, and its EER diagram.
* `/img`: Contains image assets.
* `/lib`: Contains a collection of PHP functions that are commonly used across all pages.
* `/pages`: Contains all the pages that a user can navigate through the tool, written in PHP.
* `/sample navigation`: Contains PNG images of a simple usage guide.
* `/templates`: Contains HTML templates that can be used dynamically for leave request e-mails.


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

## Example Navigation

### Use Case 1 (Employee) - Submit a Request

> The employee logs into the tool on the URL: __http://localhost__ with his/her credentials provided by the company (Email: __employee@company.com__, Password: __password__).

![1](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%201/1%20-%20E-Leave%20-%20Login.png?raw=true)

> He/She enters the main tool dashboard.

![2](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%201/2%20-%20E-Leave%20-%20Dashboard%20-%20Empty.png?raw=true)

> The employee clicks the `Submit Request` button to create a new application, filling out all the fields.

![3](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%201/3%20-%20E-Leave%20-%20Submit%20Request.png?raw=true)

>The new application is added to the dashboard, with the `Pending` status.

![4](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%201/4%20-%20E-Leave%20-%20Dashboard.png?raw=true)

> Meanwhile, the corresponding supervisor receives an e-mail and either accepts or rejects the employee's request.

![5](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%201/5%20-%20MailHog.png?raw=true)

> The supervisor clicks either the `Accept` or the `Reject` button.

![6](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%201/6%20-%20Request%20accepted.png?raw=true)

> The employee receives an e-mail with information about the outcome of his/her request.

![7](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%201/7%20-%20MailHog%20-%20Accepted.png?raw=true)

> Going back to the dashboard, the status of the request is updated.

![8](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%201/8%20-%20E-Leave%20-%20Dashboard%20-%20Accepted.png?raw=true)

### Use Case 2 (Admin) - Create a User

> The administrator logs into the tool admin page on the URL: __http://localhost/admin.php__ with his/her credentials provided by the company (Email: __admin@company.com__, Password: __password__).

![1](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%202/1-%20E-Leave%20-%20Admin%20Login.png?raw=true)

> Then he/she enters the main tool admin dashboard.

![2](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%202/2%20-%20E-Leave%20-%20Admin%20Dashboard.png?raw=true)

> The admin clicks the `Create User` button to create a new user, filling out all the fields.

![3](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%202/3%20-%20E-Leave%20-%20Create%20User.png?raw=true)

>The new user is added to the admin dashboard.

![4](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%202/4%20-%20E-Leave%20-%20Admin%20Dashboard%202.png?raw=true)

### Use Case 3 (Admin) - Edit a User

> The administrator logs into the tool admin page on the URL: __http://localhost/admin.php__ with his/her credentials provided by the company (Email: __admin@company.com__, Password: __password__).

![1](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%203/1-%20E-Leave%20-%20Admin%20Login.png?raw=true)

> Then he/she enters the main tool admin dashboard.

![2](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%203/2%20-%20E-Leave%20-%20Admin%20Dashboard%202.png?raw=true)

> The admin clicks the row containing the user he/she wants to edit from the table of users, filling out all the fields.

![3](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%203/3%20-%20E-Leave%20-%20Edit%20User.png?raw=true)

>The edited user is added to the admin dashboard.

![4](https://github.com/savvas-leoussis/e-leave/blob/master/sample%20nagivation/Use%20Case%203/4%20-%20E-Leave%20-%20Admin%20Dashboard%203.png?raw=true)

## Database Schema

The `eleave` database complies with the following EER diagram:

![database](https://raw.githubusercontent.com/savvas-leoussis/e-leave/master/database/database.png)
