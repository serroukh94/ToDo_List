ToDoList
========

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

1. Installation

   - Clone or download the project: "git clone https://github.com/serroukh94/ToDo_List.git".
   - Install dependencies with: "composer install".
   - Edit the .env file to configure your database thanks to this variable: DATABASE_URL=mysql://user:pass@127.0.0.1:3306/database_name.
   - Create the database with: php bin/console doctrine:database:create.
   - Create the different tables of the database by applying the migrations: "php bin/console doctrine:migrations:migrate".
   - Install the fixtures to have a mock data demo in development: php app/console doctrine:fixtures:load --env=dev --group=dev
   - Run "symfony serve" in cmd .
   - Congratulations the project is installed correctly, you can now start using it as you wish.

2. Usage

To test the project, you can use the following account:
- username: admin
- password: test