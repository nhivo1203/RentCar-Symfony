<p align="center"><a href="https://symfony.com" target="_blank">
    <img src="https://symfony.com/logos/symfony_black_02.svg">
</a></p>

About Project
------------

Unlock Programing This is a new repository for unlock training

**The** **car for rent project** is written by Symfony version 6.1 with the instruction of **Mr. Bang
Dinh** and **Mr. Tinh Le**

This is RentCar Project built on Symfony framework.

Requirements
------------
```bash
* PHP 8.1 or higher;
* Mysql Server
* PDO-MySQL PHP extension enabled;
* and the [usual Symfony application requirements][2].
```

Installation
------------

This is not needed for contributing, but it's useful if you want to debug some
issue in the docs or if you want to read Symfony Documentation offline.

```bash
$ git clone git@github.com:nhivo1203/RentCarSymfony.git

$ cd RentCarSymfony/

$ composer install

$ symfony server:start
```

REST API
------------

The REST API to the example app is described below.

### Login

### Request

`POST /api/login`

    {
        "email": {{email}};
        "password": {{password}}
    }

### Response

    HTTP/1.1 200 OK
    Status: 200 OK
    Content-Type: application/json

    "status": "success",
    "data": {
        "id": {{id}},
        "email": {{email}},
        "roles": {
            "role": {{roles}}
        },
        "token": {{token}}
    }

### Get list of all Cars

### Request

`GET /api/cars/all`

### Response

    HTTP/1.1 200 OK
    Status: 200 OK


    "status": "success",
    "data": {
        [{{cars}}]
    }

### Get Car details

### Request

`GET /api/cars/{id}`

### Response

    HTTP/1.1 200 OK
    Status: 200 OK

    "status": "success",
    "data": {
        "id": {{id}},
        "name": {{name}},
        "description": {{description}},
        "color": {{color}},
        "brand": {{brand}},
        "seats": {{seats}},
        "year": {{year}},
        "createdUser": {{user.name}},
        "thumbnail": {{thumbnail.path}},
        "price": {{price}},
        "createdAt": {
            "date": "2022-06-22 00:00:00.000000",
            "timezone_type": 3,
            "timezone": "Asia/Krasnoyarsk"
        },
        "deletedAt": null
    }

### Create Car

### Request

`POST /api/cars`

    {
        "name": {{name}},
        "description": {{description}},
        "color": {{color}},
        "brand": {{brand}},
        "seats": {{seats}},
        "year": {{year}},
        "thumbnail": {{thumbnailId}},
        "price":{{price}}
    }

### Response

    HTTP/1.1 204 No Content
    Status: 204 No Content

### Update Car

### Request

`PUT or PATH /api/cars/{id}`

    {
        "name": {{name}},
        "description": {{description}},
        "color": {{color}},
        "brand": {{brand}},
        "seats": {{seats}},
        "year": {{year}},
        "thumbnail": {{thumbnailId}},
        "price":{{price}}
    }

### Response

    HTTP/1.1 204 No Content
    Status: 204 No Content

### Delete Car

### Request

`DELETE /api/cars/{id}`

### Response

    HTTP/1.1 204 No Content
    Status: 204 No Content


### Upload Image

### Request

`POST /api/image`

    Body: form-data
    {
        "thumbnail": {{file}},
    }

### Response

    HTTP/1.1 200 OK
    Status: 200 OK
    
    "status": "success",
    "data": {
        'id': {{id}},
        'path: {{path}}
    }


    


