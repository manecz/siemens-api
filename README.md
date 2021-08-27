
# Siemens PHP Exercise

I was asked to develop a small project using the Symfony Framework. 
This project consists in the development of an API that allows executing the basic functions of a CRUD.
in addition to creating this API, I decided to create a client manager, which runs all the features of the API.




## Installation

Installing & Setting up the Symfony Framework
https://symfony.com/doc/current/setup.html
    
## Run Locally

Clone the project

```bash
  git clone https://github.com/manecz/siemens-api/
```

Go to the project directory

```bash
  cd siemens-api
```

Install dependencies

```bash
  composer install
```
Rename .env.dist to .env and configure your database

Faking data (optional)

```bash
  php bin/console doctrine:fixtures:load
```


Start the server

```bash
  symfony start:server
```

  
## API Reference

#### Get all Customers

```http
  GET /api/
```
| Description                       |
|:-------------------------------- |
| Returns a single customer by id. **Required** Id of customer to fetch |


#### Get a single customer

```http
  GET /api/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | Returns a single customer by id. **Required** Id of customer to fetch |

#### Create a customer

```http
  POST /api/
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`,`email`      | `string` | creates a customer. Email is **Required** and unique.  |

#### Update a customer

```http
  PUT /api/
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`,`email`      | `string` | Updates a customer by the id given.. If **NULL** doesn't update  |

#### Delete a customer

```http
  DELETE /api/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | Removes a single customer by id|
