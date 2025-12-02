@echo off
echo Setting up Temploka Project...

composer install
npm install

echo Generating application key...
php artisan key:generate

echo Please configure your database in .env file
echo Then run: php artisan migrate --seed

echo Building assets...
npm run build

echo Setup completed! Run: php artisan serve
pause