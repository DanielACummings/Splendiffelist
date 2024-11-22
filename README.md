# Bugs
- Lists & items display to all users regardless of who created them
- No warning displayed when new item name matches original. Throws error instead
- Items get crossed out when user clicks edit button but then clicks it again or hits Enter before entring any text

# Code Design & Refactoring
- MVP
    - Create List component
    - Create Item component?
    - Check if list exists prior to creating an item for it
- Post-MVP
    - Reuse UI & logic for List & Item editing with toggling edit mode
    - Only get a single item after it's updated rather than all items for the list
    - Stop using underscores in web app variable names & continue using them in SQL

# Future Improvements for Users
Functionality
- MVP
    - Keep user from having to click in edit fields to start typing for items & lists
- Post-MVP
    - Allow for lists to be shared with other users
    - Allow for lists to be publicly viewable & editable (only by crossing out--no renaming, deleting, or adding?)
    - Create list templates that can be added to lists when the lists are created
    - Allow for all minimized items to be un-crossed out
    - Allow for all minimized lists to be un-crossed out?
    - Let list templates be added to existing lists
    - Let users create multiple collections of lists where each collection has its own page that is clicked on like the current dashboard tab
UI
- MVP
    - Remove Laravel logos
    - Remove Laravel links from homescreen & add info about SplendifeList
    - Remove Laravel from titles & everywhere else
    - Smaller item buttons & maybe smaller list ones? Depends on edit mode below
    - Implement minimal version of edit mode?
        - Only applicable to a single list
        - List name automatically displays its edit name input field prepopulated with original name?
        - Maybe also turn item names into input fields so they don't need edit buttons
        - Delete buttons appear for items
            - Also make edit buttons appear if not all item names turn to input fields
        - Lists always have their delete & edit buttons
    - Use popup windows other than built in browser ones or use another visual cue for confirming deletion
    - Make list names larger
    - Prepopulate item & list edit name fields with the existing name
    - Put delete & edit buttons to the right of list names with the edit button closest to the list name?
    - Center list names in their column?
- Post-MVP
    - Make hovering nav bar with list names so users can jump to any list with a single click/tap
    - Use masonry layout for lists
    - Put crossed out items in thier own section?
    - Put crossed out lists in their own, minimized section?
    - Deleting/editing (Edit mode allows editing & deleting of lists & items)
        - List deletion requires confirmation
        - Item deletion doesn't
        - Can't cross out items in this mode?
        - Don't have to confirm changes for each & every item changed (when changing multiple item names, for example). Instead, all changes are saved when edit mode is closed
        - Don't delete immediately. Change text to red (& maybe sort to top of list in a "To be deleted" section)
        - X to delete should appear next to all lists & items in edit mode
        - For long names with no whitespace, start the text at the level as the delete & edit buttons
        - For long names with or without whitespace, wrap them so they don't go beneath the delete & edit buttons on the 2nd & subsequent lines
        - With small screen, make selected nav bar item visible
        - More precisely define number of columns for varying screen sizes (especially on the smaller end)
        - Make input fields just small enough to fit placeholder text then expand them as user types?
    - For long names that wrap without whitespace, use a dash between the wrapped lines
    - Update home screen to have local browser storage version of the app so it can be demoed or even used indefinitely without signing up
    - Migrate data from local storage to DB option (& an easy to see visual cue for knowing (when logged in) that there's local storage data that can be migrated)

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