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

### Installation (with Laravel Sail)

1. **Clone the repository**:
    ```bash
    git clone git@github.com:The-Satoshi-Circle/the-satoshi-circle-tma-api.git
    cd the-satoshi-circle-tma-api
    ```

2. **Set up environment variables**:
    ```bash
    cp .env.sail .env
    ```

3. **Edit the `.env` file**:
    - Configure your database and other necessary settings.

4. **Install dependencies with composer**:
    ```bash
      composer install
    ```
   
5. **Configure and run Laravel Sail**:
    ```bash
      php artisan sail:install
      ./vendor/bin/sail up -d
    ```

6. **Run database migrations**:
    ```bash
    ./vendor/bin/sail artisan migrate
    ```

Your API should now be running in the Docker environment provided by **Laravel Sail**.

### Installation (without Laravel Sail)

If you prefer to run the project without Laravel Sail (and Docker):

1. Ensure **PHP 8.1+**, **MySQL**, and **Composer** are installed locally.
2. **Clone the repository**:
    ```bash
    git clone git@github.com:The-Satoshi-Circle/the-satoshi-circle-tma-api.git
    cd the-satoshi-circle-tma-api
    ```

3. **Set up environment variables**:
    ```bash
    cp .env.example .env
    ```

4. **Edit the `.env` file**:
    - Configure your database and other necessary settings.

5. **Install dependencies with composer**:
    ```bash
      composer install
    ```

6. **Run database migrations**:
    ```bash
    php artisan migrate
    ```

7. **Serve your application**:
    ```bash
    php artisan serve
    ```
## 🤝 Contributing

Contributions are welcome! Fork the project, submit a pull request, or open an issue for any changes or improvements.

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.
