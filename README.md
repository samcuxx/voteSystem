# Records Management System - SamCux

## Overview

The Records Management System is designed to facilitate the management of both incoming and outgoing letters within an organization. It features distinct access levels for administrators and regular users. Administrators can manage records and oversee the system, while users can interact with records according to their roles and permissions.

## Features

- **Incoming and Outgoing Letters Management**: Add and track incoming and outgoing letters.
- **User Roles**: Distinct roles for administrators and regular users.
- **Detailed Record Keeping**: Manage records with detailed metadata and file paths.

## Login Details

- **Admin Login**:

  - **Email**: `samcux@gmail.com`
  - **Password**: `SamCux`

- **User Login**:
  - **Email**: `sam@gmail.com`
  - **Password**: `SamCux`

## Setup Instructions

### Prerequisites

1. **XAMPP**: Ensure that you have XAMPP installed on your local machine. XAMPP includes Apache, MySQL, and PHP, which are necessary to run the system.

### Installation Steps

1. **Download and Install XAMPP**:

   - Download XAMPP from [apachefriends.org](https://www.apachefriends.org/index.html) and install it on your machine.

2. **Configure XAMPP**:

   - Start Apache and MySQL from the XAMPP Control Panel.

3. **Set Up the Database**:

   - Open [phpMyAdmin](http://localhost/phpmyadmin/) in your browser.
   - Create a new database named `kma_records_management`.
   - Import the provided SQL dump file into this database.

4. **Import SQL Dump**:

   - Go to the `Import` tab in phpMyAdmin.
   - Choose the SQL dump file and import it into the `kma_records_management` database.

5. **Configure Database Connection**:

   - Locate the `config.php` file in your project directory (usually found in the `includes` or `config` folder).
   - Update the database connection settings with the following credentials:
     ```php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "kma_records_management";
     ```

6. **Access the System**:
   - Open your web browser and navigate to `http://localhost/your_project_folder`.

## Database Structure

The database `kma_records_management` consists of the following tables:

### 1. `departments`

- **Purpose**: Stores information about different departments.
- **Columns**:
  - `id` (INT, Primary Key): Unique identifier for the department.
  - `name` (VARCHAR): Name of the department.

### 2. `files`

- **Purpose**: Stores information about files (incoming and outgoing letters).
- **Columns**:
  - `id` (INT, Primary Key): Unique identifier for the file.
  - `department_id` (INT, Foreign Key): Reference to the department the file belongs to.
  - `transaction_id` (INT, Foreign Key): Reference to the associated transaction.
  - `reference_number` (VARCHAR): Unique reference number for the file.
  - `date` (DATE): Date of the file.
  - `year` (INT): Year of the file.
  - `month` (INT): Month of the file.
  - `title` (VARCHAR): Title of the file.
  - `file_path` (VARCHAR): Path to the file on the server.
  - `file_type` (ENUM): Type of file (incoming or outgoing).

### 3. `transactions`

- **Purpose**: Records various transactions associated with departments.
- **Columns**:
  - `id` (INT, Primary Key): Unique identifier for the transaction.
  - `department_id` (INT, Foreign Key): Reference to the department.
  - `name` (VARCHAR): Name of the transaction.
  - `reference_no` (VARCHAR): Reference number for the transaction.

## Contact

For any issues or inquiries, please contact:

- **Phone**: +0531114854
- **Email**: sa.devwin@gmail.com
- **WhatsApp**: +0531114854

---

Feel free to modify or expand upon this `README.md` to better suit your project's specific needs or any additional information you might want to include.
