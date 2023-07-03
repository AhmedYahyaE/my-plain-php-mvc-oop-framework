# My personal Plain PHP MVC OOP Framework (similar to Laravel)
This is my personal backend vanilla PHP Framework which follows the MVC Design Pattern (Model-View-Controller Architecture), and is entirely Object-oriented (OOP). This robust framework is made to mimic and be mostly similar to the famous Laravel framework. I promise you if you understand the inner workings of this framework, you'll very well understand how Laravel works under the hood. This project script is written entirely in plain PHP (OOP) and aims to demonstrate the implementation of an MVC framework imitating the well-known market frameworks such as Laravel and CodeIgniter without relying on any external libraries or frameworks.

Frontend technologies used: AJAX, jQuery, JavaScript and Bootstrap (Responsive Design/Mobile First Design).

## Screenshots:
***Products Index Page:***

![products-index](https://github.com/AhmedYahyaE/my-plain-php-mvc-oop-framework/assets/118033266/3aad01c6-853e-4eaf-8a17-5e4f2a1e8d71)

## Features:
1- MVC Design Pattern (Separation of Concerns).

2- Service Container Class (Similar to Laravel's).

3- Routing System (Router Class).

4- Custom Autoloading Class (No external Composer Autoloader).

5- Custom DotENV file reader class (No external DotENV file reader package).

6- Middlewares implementation.

7- Protected Routes (using a custom Authentication Middleware).

8- Entry Point/Script index.php file for the whole application.

9- Login System utilizing a custom Session Class.

10- Session Flash Messages.

11- AJAX Live Search.

12- Multilingual Support.

13- Custom Pagination implementation.

9- CRUD Operations.

11- File Upload.

12- Registration, Validation, Authentication and Authorization.

13- Responsive / Mobile first Design using Bootstrap.

## Application Routes:
All the application routes are defined in the [index.php](public/index.php) file inside the "public" folder.

## Application API Endpoints:

GET /api/produtcts &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Get All produtcs

GET /api/products/{id} : Get a Single product

***\*\*Note: You can test the framework API Endpoints using Postman, here is the Postman Collection file [Postman Collection](<Postman Collection of API Endpoints/My Plain PHP MVC OOP Framework API.postman_collection.json>) .json file.***

## Installation & Configuration:
1- Clone the project or download it.

2- Create a MySQL database named **\`my_plain_php_mvc_oop_framework\`** and import the database SQL Dump file from [Database SQL Dump file](<Database - my_plain_php_mvc_oop_framework/my_plain_php_mvc_oop_framework database - SQL Dump File - PhpMyAdmin Export.sql>).

3- Navigate to the ***.env*** file [.env](.env) and configure/edit/update it with your MySQL database credentials and other configuration settings.

4- Navigate to the project "public" folder/directory (where the Entry Point [index.php](public/index.php) file is placed) by using the **`cd`** terminal command, and then start your PHP built-in Development Web Server by running the command: **`php -S localhost:8000`**.


***\*\*Note: Whatever your Web Server is, you must configure its Web Root Directory to be the application "public" folder which contains the [index.php](public/index.php) file (Entry Point) in order for the application Routing System to function properly.***

5- Here are the ready-to-use registered user account credentials you can readily use is (for both **Frontend** and **Admin Panel**):

> **Email**: **ahmed.yahya@gmail.com**, **Password**: **123456**

## Contribution:
Contributions to my plain PHP/MySQL MVC OOP application are most welcome! If you find any issues or have suggestions for improvements or want to add new features, please open an issue or submit a pull request.
