# Admin Panel Package 

This package is provide the admin auth, auth api and frontend of the panel.  Install the passport package.  

## Installation

```bash
composer require stackup/auth

# publish the files 
php artisan vendor:publish --tag=files --force

# migrate the passport and admin migration
php artisan migrate

# run the seed of admin
php artisan db:seed

# install the passport 
php artisan passport:install
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[Open Source]
