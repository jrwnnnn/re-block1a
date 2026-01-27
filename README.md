<h1 align="center">Block1A</h1>

<p align="center"><i>Block1A official source repo.</i></p>

<p align="center">
   <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white">
   <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white">
  <a href="https://opensource.org/license/mit"><img src="https://img.shields.io/badge/MIT-green?style=for-the-badge"></a>
   <a href="https://github.com/jrwnnnn/priv-block1a"><img src="https://img.shields.io/github/stars/jrwnnnn/priv-block1a?style=for-the-badge"></a>
</p>

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
   git clone https://github.com/jrwnnnn/re-block1a.git
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
     http://localhost/re-block1a/index.php
     ```
## License
This project uses the MIT license. For details, please refer to the LICENSE file.


<img src="https://forthebadge.com/badges/built-with-love.svg">
