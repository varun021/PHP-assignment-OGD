# Employee Management System

A simple web application to manage employees, their departments, and contact details using PHP, MySQL, and AJAX.

## Features

- Add employees with name, email, department, and phone number
- Real-time form submission with loading indicator
- Data validation and error handling
- Multi-table database relationships

## Setup

1. **Database Setup**
```sql
CREATE DATABASE employee_db;
USE employee_db;

CREATE TABLE departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    department_id INT NOT NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

CREATE TABLE contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

-- Add sample departments
INSERT INTO departments (name) VALUES 
    ('IT'), ('HR'), ('Sales'), ('Marketing');
```

2. **Configure Database**
- Open `db_functions.php`
- Update database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'employee_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

3. **Run Application**
- Place files in web server directory
- Access through: `http://localhost/php-assignment-ogd/add_employee.php`

## Project Files

- `add_employee.php` - Frontend form
- `submit_employee.php` - AJAX handler
- `db_functions.php` - Database operations
