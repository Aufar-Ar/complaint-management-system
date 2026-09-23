<div align="center">

# Complaint Management System

This system was built using the Laravel framework, which employs the MVC (Model-View-Controller) architecture, and utilizes a MySQL database. With this system in place, employees do not need to download any additional apps, while technicians can fairly assign available support tickets, ensuring that the workload is distributed more evenly and measurably.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

</div>

---

## Key Features
*   **Employee Module**: Built within the `/employee/` directory. Features include:
    *   **Ticket Submission**: Processes POST requests containing complaint details. Implemented file upload logic with strict validation (checking file size and allowed extensions: JPG, PNG, PDF) and securely moving files to the `/uploads/` directory.
    *   **Dashboard**: Fetches and renders all complaints linked to the active `$_SESSION['user_id']`.
*   **Technician Module**: Built within the `/technician/` directory. Features include:
    *   **Dashboard & Queue**: Implemented complex SQL queries with `JOIN` statements to fetch high-level system statistics, the open unassigned queue, and tickets actively claimed by the technician.
    *   **Action Handling**: Built endpoints for claiming tickets (updating `technician_id` and setting status to `in_progress`) and resolving tickets.
*   **Notification System**: Integrated throughout the application modules. When a technician claims or resolves a ticket, a background SQL insert automatically generates a notification record for the employee.

---

## Tech Stack

- **Backend Framework:** Laravel 11.x (PHP 8.2+)
- **Frontend Styling:** Vanilla CSS
- **Templating Engine:** Laravel Blade Templates
- **Database:** MySQL / MariaDB (via XAMPP)

---

## Getting Started

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek ini di lingkungan lokal Anda:

### 1. Clone Repositori
```bash
git clone https://github.com/Aufar-Ar/complaint-management-system.git
cd complaint-management-system
```

### 2. Install Dependensi PHP & JavaScript
```bash
composer install
npm install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Open the `.env` file and adjust your database settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=it_complaints_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key & Migrate the Database
```bash
php artisan key:generate
php artisan migrate --seed
```

### 6. Run the Dev Server
Run Laravel Server:

**Terminal 1 (Laravel Server):**
```bash
php artisan serve
```

Website can be accessed at: `http://127.0.0.1:8000` 🚀

---

## Demo Accounts

| Role | Username | Password |
| :--- | :--- | :--- |
| **Technician** | `tech1` | `password` |
| **Employee** | `emp1` | `password` |

---

## License

This project is licensed under [MIT License](LICENSE).
