# ⚡ The Satoshi Circle TMA API

This repository contains the backend API for **The Satoshi Circle TMA** Telegram Mini App, developed using **Laravel 11** and **Botman** to handle interactions with the app.

**Telegram Mini App**: [The Satoshi Circle TMA](https://github.com/The-Satoshi-Circle/the-satoshi-circle-tma)

## 🛠️ Technologies Used

- **Laravel 11**: A PHP framework for building modern web applications.
- **Laravel Sail**: Docker-based development environment for Laravel.
- **Botman**: A library for building chatbot interfaces for Telegram and other platforms.

## 🚀 Getting Started

### Prerequisites

- **Docker** and **Docker Compose**: Required for running Laravel Sail.
- **PHP** (optional, if not using Sail directly).
- **Composer**: Dependency manager for PHP.

### Installation

1. **Clone the repository**:
    ```bash
    git clone https://github.com/The-Satoshi-Circle/tma-api
    cd tma-api
    ```

2. **Set up environment variables**:
    ```bash
    cp .env.example .env
    ```

3. **Edit the `.env` file**:
    - Configure your database and other necessary settings.

4. **Install dependencies using Laravel Sail**:
    ```bash
    ./vendor/bin/sail up -d
    ./vendor/bin/sail composer install
    ```

5. **Run database migrations**:
    ```bash
    ./vendor/bin/sail artisan migrate
    ```

6. **Run the application**:
    ```bash
    ./vendor/bin/sail up
    ```

Your API should now be running in the Docker environment provided by **Laravel Sail**.

### Optional: Running without Sail

If you prefer to run the project without Docker:

1. Ensure **PHP 8.0+**, **MySQL**, and **Composer** are installed locally.
2. Follow steps 1-5 using `php` and `composer` commands instead of Sail.

## 🤝 Contributing

Contributions are welcome! Fork the project, submit a pull request, or open an issue for any changes or improvements.

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.
