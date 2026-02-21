# GymAdmin Gym Management System

A lightweight, modern web application for managing gym memberships, trainers, and access control. Built with PHP, MySQL, and styled with Tailwind CSS for a fully responsive administrative dashboard.

## Features

- **Admin Dashboard:** Centralized overview of gym statistics, revenue tracking, and member distribution.
- **Member Management:** Register, edit, and delete members. Track membership validity and status (Active, Expiring Soon, Expired).
- **Trainer Management:** Register trainers and assign them to specific gym members.
- **Access Control:** Secure login system for administrators. 
- **Responsive Design:** Mobile-first layout ensures the admin panel is usable on phones, tablets, and desktops.

## Tech Stack

- **Frontend:** HTML5, CSS3, JavaScript, Tailwind CSS (via CDN)
- **Backend:** PHP 8.x
- **Database:** MySQL / MariaDB
- **Icons & Fonts:** FontAwesome, Google Fonts (Inter)

## Setup & Installation (XAMPP)

1. **Install XAMPP** (if you haven't already) and start the **Apache** and **MySQL** modules from the XAMPP Control Panel.
2. Clone or extract this repository into your XAMPP `htdocs` folder (usually located at `C:\xampp\htdocs\`). 
   *For example, if you place it in a folder called `gym`, the path should be `C:\xampp\htdocs\gym\`.
3. Open your browser and go to `http://localhost/phpmyadmin/`.
4. Create a new database named `gym`.
5. Click on the newly created `gym` database, go to the **Import** tab, select the `database/gym.sql` file from this project, and click **Import**.
6. Review `config.php` and ensure the database credentials match your local environment. For a default XAMPP installation, they are:
   - Server: `localhost`
   - Username: `root`
   - Password: *(leave blank)*
   - Database: `gym`
7. Open your browser and navigate to the project directory (e.g., `http://localhost/gym/` or `http://localhost/gym_menagement_system-main/` depending on what you named the folder).

## Usage

**Default Admin Credentials:**
- **Username:** `admin`
- **Password:** `password123`

*(Note: In a production environment, ensure you create a new admin and delete the default credentials).*

## License & Copyright

© 2026 Darko Dukovski. All Rights Reserved.

This project is created for portfolio and demonstration purposes only. It is closed-source software. You may view the code, but you are not permitted to copy, distribute, modify, or use any part of it for commercial purposes (such as a SaaS) without explicit written permission from the author.
