# Symfony 42 Piscine - Module 03: Sessions

## Right after cloning
- 1) => run `composer update`;
- 2) => run `php bin/console doctrine:schema:update --force`

## Useful Commands

- `php bin/console doctrine:schema:update --force` => Forces the database update without using migration files.
- `composer update` => Updates/Installs every package inside the compose.json.

## Project Main Routes

- `/e01/homepage` - Main page displaying the user's status and buttons for login, registration, or logout.
- `/e01/register` - Page for creating a new user account.
- `/e01/login` - Page for logging into an account.
- `/e01/logout` - Logs out the currently connected user.

- `/e02/admin/register` - Page for creating a new administrator account.
- `/e02/admin` - Admin panel listing all users/administrators with buttons to delete them.


