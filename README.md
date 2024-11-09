# Mytheresa Challenge

## Installation

Clone the project

```bash
git clone git@github.com:victorqm98/mytheresa-challenge.git
```

Use docker to run the project

```bash
make start
```

Enter in docker to install dependencies

```bash
make enter
```

To install the composer dependencies use

```bash
composer install
```

Verify your username in console

```bash
whoami
```

You will need permissions to create folders

```bash
sudo chown -R www-data:www-data app
sudo chown -R user:user app
```

Create the necessary directories and set permissions

```bash
sudo mkdir -p app/var/cache app/var/log
sudo chown -R www-data:www-data app/var/cache app/var/log
sudo chmod -R 775 app/var/cache app/var/log
```

Connect the database

- Database:mytheresa-challenge
- UserName:challenge
- Password:challenge

Run migrations to create the tables:

```bash
php app/bin/console doctrine:migrations:migrate
```

## Usage

This are the API endpoints to try the code. (Also sent in json format to import directly in Postman.)

- 127.0.0.1/health

## Tests

Navigate to the project root and run `make tests` or `docker exec mytheresachallenge_php_1 bash -c "composer test"` after installing all the composer dependencies.

