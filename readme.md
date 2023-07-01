# About this repo
This repo contains a Simfony project. To run it on your local, please follow these steps:

1. Install [git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
2. Checkout the repository:
    ```shell
    git clone git@github.com:zahramirzaagha/virtualschool-revisited.git
    ```
3. Install [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)
4. Install [node package manager (npm)](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)
5. Install [PHP](https://www.php.net/manual/en/install.php)
6. Install [MySQL](https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/)
   1. Choose blank for the root password
   2. Create a database and name it `app`
   3. navigate to `project-root/.env` and change `DATABASE_URL` to `"mysql://root:@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"`
7. Install [Symfony CLI](https://symfony.com/download#step-1-install-symfony-cli)
8. Open a shell and switch to the root directory of the project
9. Install PHP packages: `composer install`
10. Install node packages: `npm install`
11. Run the migration script: `symfony console doctrine:migrations:migrate`
12. Run the data fixture to seed the database with some test data: `symfony console doctrine:fixtures:load`
13. Build the assets: `npm run watch`
14. Start the Symfony server: `symfony server:start -d`
15. Go to your browser and navigate to `http://localhost:8000/en`
    1. The port might be different on your machine. Please check the `server:start` command output for the exact URL.
16. Start browsing
    1. Database is seeded with some random users and courses
    2. All users have the same default password (`123456`)