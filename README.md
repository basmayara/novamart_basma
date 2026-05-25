# NovaMart — E-Commerce App

A full-stack e-commerce web application built with Symfony 7.

## Features
- Product catalog with categories
- Shopping cart (session-based)
- User authentication (register/login)
- User profile with order history
- Responsive design with Bootstrap 5

##  Technologies
- **Backend:** PHP 8 / Symfony 7
- **ORM:** Doctrine
- **Frontend:** Twig, Bootstrap 5
- **Database:** MySQL
- **Infrastructure:** Docker, Nginx

## Installation
```bash
git clone https://github.com/basmayara/novamart_basma.git
cd novamart_basma
composer install
cp .env .env.local
php bin/console doctrine:migrations:migrate
symfony serve
```

## Author
Basma — Symfony Developer