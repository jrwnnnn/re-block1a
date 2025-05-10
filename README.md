# Block1A <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/1067px-PHP-logo.svg.png?" style="height:30px;"> <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d5/Tailwind_CSS_Logo.svg/2560px-Tailwind_CSS_Logo.svg.png" style="margin-left: 2rem; height:30px;">

<!-- PROGRAMMING LANGUAGE ICONS
HTML: https://upload.wikimedia.org/wikipedia/commons/thumb/6/61/HTML5_logo_and_wordmark.svg/512px-HTML5_logo_and_wordmark.svg.png
JAVA: https://upload.wikimedia.org/wikipedia/en/thumb/3/30/Java_programming_language_logo.svg/1200px-Java_programming_language_logo.svg.png
Python: https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Python-logo-notext.svg/1869px-Python-logo-notext.svg.png
PHP : https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/1067px-PHP-logo.svg.png?20180502235434
mySQL: https://upload.wikimedia.org/wikipedia/labs/8/8e/Mysql_logo.png

--->

## Prerequisites

Make sure the following are installed:

- [PHP (recommended: PHP 8.x)](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/)
- A local web server like [XAMPP](https://www.apachefriends.org/download.html) or [Laragon](https://laragon.org/download/)

## Setup Instructions
This project is a PHP-based website using Composer for dependency management and Tailwind CSS for styling. The following instructions will guide you through setting up the project on your local machine for development and testing purposes.

1. **Clone the repository**

   ```bash
   git clone https://github.com/jrwnnnn/block1a.git
   cd block1a
   ````

2. **Install `vlucas/phpdotenv`**

   This is needed to manage environment variables:

   ```bash
   composer require vlucas/phpdotenv
   ```

3. Create a `.env` file in the root directory of the project. It should contain environment-specific variables such as:

   ```env
   DB_HOST=localhost
   DB_NAME=your_database
   DB_USER=root
   DB_PASS=
   ```

4. Ensure the `.env` file is loaded in your PHP project by including the following in your main file (e.g., `index.php` or a config file):
   
   ```php
   require_once __DIR__ . '/vendor/autoload.php';
   $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
   $dotenv->load();
   ```

5. **Install Node dependencies**

   In the project folder, run the following command in the terminal to install Node dependencies:

   ```bash
   npm install
   ```

6. **Run Tailwind CSS**

   Compile TailwindCSS by running:

   ```bash
   npm run tailwind
   ```

7. **Start the website**

   * Open your local server (e.g., XAMPP, Laragon).
   * Point it to your project folder.
   * Visit the site at:

     ```
     http://localhost/block1a/index.php
     ```
