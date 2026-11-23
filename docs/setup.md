# Setup Document

## Swagger Commands to Generate Swagger file. 

1. First time setup only once 

Add the `l5-swagger.php` inside the config folder
```bash 
php artisan vendor: publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

2. Generate the docs, everytime you change an annotation 
```bash 
php artisan l5-swagger:generate
```

View `Swagger` Page at the following url 
```bash 
localhost:8000/api/documentation
```