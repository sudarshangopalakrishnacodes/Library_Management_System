# LIBRARY MANAGEMENT SYSTEM — PHP-Based Book Portal

A lightweight **Library Management System** built with **core PHP**, **HTML**, and **CSS**, designed for simple use with a database.
Ideal for beginners learning server-side scripting, form handling, and session-based login systems.

Librarians can **register, login, add or remove books**, while users can **register, login, borrow, return**, and view their borrowed books

---

## Features

- User Registration & Login
- Session-based authentication
- Add / Remove Books (Librarian role)
- Borrow / Return Books (User role)
- View list of available and borrowed books
- Clean UI using pure HTML & CSS
- Form validation & error messaging
- Uses MySql DB

---

## Tools & Requirements

To run this project locally, you’ll need:

- [XAMPP](https://www.apachefriends.org/index.html)
  - Start **Apache server** only (MySQL not needed)
- A modern browser (Chrome, Firefox, Edge)
- Any IDE or text editor (VS Code recommended)
  -- MySql which comes in XAMPP stack

---
## 🔧 MySQL Database Setup

Run the following SQL commands in **phpMyAdmin** or your preferred MySQL client after starting MySQL:


CREATE DATABASE IF NOT EXISTS library_system;
USE library_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('customer', 'librarian') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    author VARCHAR(100) NOT NULL,
    total_copies INT NOT NULL CHECK (total_copies > 0),
    available_copies INT NOT NULL DEFAULT 0,
    added_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE borrowed_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    return_date DATE DEFAULT NULL,
    is_returned BOOLEAN DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE
);

## How to Run

### Using XAMPP (recommended)

1. Open **XAMPP Control Panel**
2. Start the **Apache server**
3. Start the **MySQL DB**
4. Place this project inside:
   ```
   C:\xampp\htdocs\LibraryManagementSystem
   ```
5. Open your browser and visit:
   ```
   http://localhost/LibraryManagementSystem/
   ```

---

## Real-World Use Cases

- **Student Projects**: Learn login systems, session handling, and simple data flows
- **Practice**: Ideal sandbox for improving form validation and error handling in PHP
- **Portfolio Boost**: Add styling and features to make it your own

---

## Credits

Built using:

- PHP (core)
- HTML/CSS (Responsive layout & clean styling)
- Sessions & Superglobals (`$_POST`, `$_SESSION`, etc.)
- Form validation and error handling

---

## **Notice**

If this project helps you learn or you feature it in your portfolio, I'd love to connect!  
Email: **sudarshangopalakrishna@gmail.com**  
LinkedIn: [linkedin.com/in/sudarshan-gopalakrishna-55726a358]
