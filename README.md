# CpE Registry

CpE Registry is a PHP and MySQL contact tracing application for the Department of Computer Engineering. It lets visitors register once, sign in for each visit, sign out when leaving, and gives administrators a searchable record of visit logs.

## Features

- Visitor registration for USC and non-USC visitors
- Auto sign-in after successful registration
- Returning visitor sign-in using an ID number or visitor reference code
- Visitor sign-out tracking
- Admin login with hashed passwords
- Admin dashboard for filtering visit logs by ID number, name, address, date, and time
- MySQL schema with visitor, visit log, and admin tables
- Project documentation with ERD, wireframes, and use case diagram

## Tech Stack

- PHP
- MySQL or MariaDB
- PDO for database access
- HTML, CSS, and JavaScript
- XAMPP for local development

## Requirements

- XAMPP or another local PHP/MySQL server
- PHP 7.4 or later
- MySQL 5.7+ or MariaDB 10.3+
- A web browser

## Local Setup

1. Place the project folder inside your XAMPP `htdocs` directory.

   Example:

   ```text
   C:\xampp\htdocs\CpE-Registry
   ```

2. Start Apache and MySQL from the XAMPP Control Panel.

3. Create the database by importing the schema file.

   Open phpMyAdmin, create or select a database, then import:

   ```text
   database/schema.sql
   ```

   The schema also creates the `cpe_registry` database automatically if it does not exist.

4. Check the database connection settings in:

   ```text
   config/db.php
   ```

   Default XAMPP settings are already used:

   ```php
   $host = 'localhost';
   $db   = 'cpe_registry';
   $user = 'root';
   $pass = '';
   ```

5. Open the app in your browser.

   ```text
   http://localhost/CpE-Registry/
   ```

## Default Admin Account

The database schema seeds one admin account:

```text
Username: admin
Password: Admin@123
```

For a real deployment, change this password immediately. The password stored in the database is hashed, so create a new hash using PHP's `password_hash()` function before replacing it.

## Main Pages

```text
index.php              Landing page
register.php           New visitor registration
signin.php             Visitor sign-in
signout.php            Visitor sign-out
welcome.php            Visitor confirmation page
admin/login.php        Admin login
admin/dashboard.php    Admin visitor search dashboard
```

## Project Structure

```text
CpE-Registry/
|-- admin/             Admin login, logout, dashboard, and search logic
|-- assets/            CSS, JavaScript, and image files
|-- config/            Database connection file
|-- database/          MySQL schema
|-- Documentation/     ERD, diagrams, and wireframes
|-- includes/          Shared header, footer, auth, and visit log helpers
|-- index.php          Public landing page
|-- register.php       Visitor registration page
|-- signin.php         Visitor sign-in page
|-- signout.php        Visitor sign-out page
`-- welcome.php        Visitor confirmation page
```

## Database Overview

The app uses three main tables:

- `visitors` stores each registered visitor's basic information.
- `visit_logs` stores sign-in and sign-out records for every visit.
- `admin` stores administrator credentials with hashed passwords.

More details are available in:

```text
Documentation/ERD.md
```

## Notes

- This project is intended for local academic use.
- Do not use the default admin password in production.
- Keep database credentials private if the project is deployed.
- Review validation, authentication, and access control before using this outside a school demo environment.
