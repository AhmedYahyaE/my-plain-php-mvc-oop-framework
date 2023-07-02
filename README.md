# My own Plain PHP MVC OOP Framework (along the lines of Laravel)
A plain PHP and MySQL advanced blog application which follows the MVC Design Pattern (Model-View-Controller Architecture i.e. Separation of Concerns), and is completely Object-oriented (OOP). This project script is written entirely in plain PHP and aims to demonstrate the implementation of a blog system without relying on any external libraries or frameworks.

Frontend technologies used: jQuery, JavaScript, AJAX and Bootstrap (Responsive Design/Mobile First Design).

## Screenshots:

***Products Index Page:***

![products-index](https://github.com/AhmedYahyaE/my-plain-php-mvc-oop-framework/assets/118033266/3aad01c6-853e-4eaf-8a17-5e4f2a1e8d71)


## Features:
1- MVC design pattern, Routing System, Service Container, Middlewares, Pagination Class, Entry Point/Script file (index.php) for the whole application, ...

2- User Registration, Authentication and Authorization.

3- Login System (Session Management).

4- Both Server-side and Client-side Validation.

5- Admin Panel for managing blog users, posts and comments.

6- User profile management.

7- Create, update, and delete blog posts.

8- Categories and tags for organizing blog posts. Also, commenting system for blog posts.

9- CRUD Operations.

10- User Roles and Permissions.

11- File Upload.

12- Responsive / Mobile first Design.

## Application URLs:
1- Frontend: The public-facing website can be accessed at https://www.your-domain-example.com/. This is where regular customers/users/members can view/read blog posts, add posts, comment on them, and interact with the website, .... The frontend URL is typically accessible to all visitors of the website. Replace https://www.your-domain-example.com/ with the actual domain name or localhost address where you have deployed the application.

2- Admin Panel: The Admin Panel for managing the blog is available at https://www.your-domain-example.com/admin/login or https://www.your-domain-example.com/admin. This is a secure area accessible only to authorized administrators. It provides access to the administrative functionalities of the blog application. It is designed for authorized users with administrative privileges to manage the blog posts, comments, and user accounts. Only authenticated administrators can access the admin panel. The Admin Panel URL is protected and restricted to a specific set of users. Again, make sure to replace https://www.your-domain-example.com/ with the appropriate domain name or localhost address.

## Application Routes:
All the application routes are defined in the [Application Routes](App/index.php) file.

## Installation & Configuration:
1- Clone the project or download it.

2- Create a MySQL database named **\`blog\`** and import the database schema from [blog database - PhpMyAdmin Export.sql](<Database - blog/blog database - SQL Dump File - PhpMyAdmin Export.sql>) SQL Dump file. Navigate to '**`Database - blog`**/**`blog database - SQL Dump File - PhpMyAdmin Export.sql`**' SQL Dump file.

3- Navigate to the database connection configuration file in '**`config.php`**' file and configure/edit/update the file with your MySQL database credentials and other configuration settings.

4- Navigate to the project root directory by using the **`cd`** terminal command, and then start your PHP built-in Development Web Server by running the command: **`php -S localhost:8000`**.

5- In your browser, go to http://localhost:8000/ (**Frontend**) and http://localhost:8000/admin or http://localhost:8000/admin/login (**Admin Panel**). N.B. All the application routes are defined in the [Application Routes](App/index.php) file.

6- Here are the ready-to-use registered user account credentials you can readily use is (for both **Frontend** and **Admin Panel**):

> **Email**: **ahmed.yahya@gmail.com**, **Password**: **123456**

## Contribution:
Contributions to my plain PHP/MySQL MVC OOP application are most welcome! If you find any issues or have suggestions for improvements or want to add new features, please open an issue or submit a pull request.