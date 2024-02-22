# KCB Loan Manager

KCB Loan Manager is a PHP project designed to help you manage all your loans and keep track of payments conveniently.

## Installation

1. **Clone the Repository:**
   Clone this repository to your local machine using the following command:
   ```bash
   git clone https://github.com/mesh-dell/loan-system.git
   ```

2. **Install Dependencies:**
   Use Composer to install project dependencies. If you don't have Composer installed, [download and install it](https://getcomposer.org/download/).
   ```bash
   composer install
   ```

3. **Create .env File:**
   Create a `.env` file in the project root directory and add your database credentials. You can use the `.env.example` file as a template:
   ```
   DB_HOST=your_database_host
   DB_PORT=your_database_port
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

4. **Run Migrations:**
   Once your `.env` file is configured, run database migrations to set up the database schema:
   ```bash
   php artisan migrate
   ```

5. **Start the Application:**
   You can now start the PHP development server and access the application in your web browser:
   ```bash
   php -S localhost:8000 -t public
   ```

## Usage

1. **Create Account:**
   Sign up for an account to start managing your loans.

2. **Login:**
   Log in to your account to access the loan management dashboard.

3. **Create Loan:**
   Add your loans to the system to keep track of them effectively.

4. **Make Payments:**
   Record your loan payments to stay on top of your financial obligations.

5. **Generate Reports:**
   Compile reports to get insights into your loan history and payment status.

## Contributing

Contributions are welcome! If you find any bugs or have suggestions for improvement, please open an issue or submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).

---

Feel free to customize this README according to your project's specific requirements and features. If you have any questions or need further assistance, let me know!
