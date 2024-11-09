# Future Improvements for Users
Functionality
- User has to click in edit field for new items to start typing
- Display list edit name field only after edit button is clicked
- Prepopulate item & list edit name fields with the existing name
- Make input fields just small enough to fit placeholder text then expand them as user types?

UI
- Make text visible while typing in input fields
- Use multiple rows of lists
- Make give input fields grey background?
- Make delete & edit buttons smaller
- Add vertical padding between items
- Add padding between buttons & input fields
- Add padding between buttons & names of lists & items
- Make pencil & plus signs larger
- Make add & edit buttons the same size

# Bugs
- No warning displayed when new item name matches original

# Code Design & Refactoring
- Check if list exists prior to creating an item for it
- Create List component
- Cleanly reuse UI & logic for List & Item editing
- Create Item component?
- Stop using underscores in web app variable names while continuing to use them in SQL
- Only get a single item after it's updated rather than all items for the list

# Setup
- composer create-project laravel/laravel SplendifeList-LaravelAndVue
- cd SplendifeList-LaravelAndVue
- cp .env.example .env (needed? Does composer create-project alreadly do that?)
- php artisan key:generate
- composer require laravel/breeze --dev
- php artisan breeze:install
- Add Node to Path with NVS
- npm install
- Set up MySQL DB (See MySQL section below)

# MySQL
## Setup
- Installing with APT: see [MySQL documentation](https://dev.mysql.com/doc/mysql-apt-repo-quick-guide/en/)
    - Use "ubuntu lunar" option for APT setup. Most recommendations onlin said to use "ubuntu jammy", but that resulted in a dependency on a package that couldn't be downloaded
- Create user with username and password of "laravel" in MySQL terminal
    - `sudo mysql -u root`
    - `CREATE DATABASE laravue;`
    - `CREATE USER 'laravue'@'localhost' IDENTIFIED BY 'laravue';`
    - `GRANT ALL PRIVILEGES ON laravue.* TO 'laravue'@'localhost';`
    - `FLUSH PRIVILEGES;`
- Make sure user is using mysql_native_password instead of auth_socket
    - `SELECT user, host, plugin FROM mysql.user WHERE user = 'your_username';`
    - Update if needed
- Start MySQL server
    - `sudo service mysql start`
- Check if server is running
    - `sudo service mysql status`
- Software versions at time of API creation
    - Linux: Linux Mint 22 Cinnamon with kernel v6.8.0-41-generic
    - MySQL: "Ver 8.0.39-0ubuntu0.24.04.2 for Linux on x86_64 ((Ubuntu))"