<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://upload.wikimedia.org/wikipedia/commons/b/be/Mapa_do_Brasil_com_a_Bandeira_Nacional.png" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>

## **About Project**

This project is a simple application to find County in Brazil by API and a SPA
This app provider two ways to find County in Brazil using two services:

- Brasil Api
- IBGE Api

>**NOTE** The IBGE Api have the Open SSL and TLS in older version, it impossibility make HTTP requests to this API, unfortunately.

## **About My Journe**

Im my journe coding and deploying this application I take much experience in backend application more than I can imagine.
I used for the first time WSL to development and combine it with a full dockerization service in Ubuntu.
In my coding I never installed nothing but Docker, which slowed my dev experience (without local php and laravel) but permited a full and complete Virtualization from application.
I like so much develop a simple Api and this challenge enrich a lot my knowledges about another stacks and dev area such Backend, DevOps and SecOps

## **Technologies**

This application uses:

- **[Google Cloud SDK](https://cloud.google.com/sdk?hl=pt-br)**
- **[Google Cloud Cli](https://cloud.google.com/sdk/docs/install?hl=pt-br)**
- **[Laravel 10](https://laravel.com/)**
- **[Laravel Sail](https://laravel.com/)** - in optional config (see docker-compose.yml)
- **[Docker](https://www.docker.com/)**
- **[PHP 8.2](https://www.php.net/releases/8.2/en.php)**
- **[Nginx](https://www.nginx.com/)**
- **[VSCode](https://code.visualstudio.com/)**
- **[WSL](https://learn.microsoft.com/pt-br/windows/wsl/install)**
- **[Ubuntu 22.04-LTS](https://ubuntu.com/download)**
- **[Postman](https://www.postman.com/)**
- **[Github Actions](https://github.com/features/actions)**
- **Deployed on Google Cloud**

## **Documentation**

### Setup of containers

1 - First, configure the environment:

```sh
make env
```

2 - Second, create the containers:

```sh
make up
```

> These steps are only for first time using this project, it will create and install all dependencies for project and setup PHP and NGINX

Now your application is running in `http://127.0.0.1:8000/`

### Test

> To test you first need to attach on container

- For attach on container run...

```
# will attach on container api_app in /var/www workdir
make attach
```

- Now you can run twice option of test
  - PHPUnit
  - Artisan Test

To Run PHPUnit just run

```
make phpunit
```

To Run Artisan just run

```
make test
```

- If you want to install something else just run on container

```sh
compose require $(ARGS)
```
