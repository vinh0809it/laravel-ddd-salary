<h1 align="center">
  Laravel API with Domain-Driven Design (DDD)
</h1>

<p align="center">
    <a href="https://laravel.com/"><img src="https://img.shields.io/badge/Laravel-10-FF2D20.svg?style=flat&logo=laravel" alt="Laravel 10"/></a>
    <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.1-777BB4.svg?style=flat&logo=php" alt="PHP 8.1"/></a>
    <a href="https://github.com/orphail/laravel-ddd/actions"><img src="https://github.com/orphail/laravel-ddd/actions/workflows/laravel-tests.yml/badge.svg" alt="GithubActions"/></a>
</p>

## 🚀 Current features
- User Domain basic CRUD
- SalaryHistory Domain basic CRUD
- Integration tests and Unit tests

## 📘 Introduction
I am learning to apply Domain-Driven Design (DDD) to Laravel, and this project is my first step. My goal is to build a standalone API server for salary management, which will be a part of my HRM (Human Resource Management) microservices architecture.

That's why this repository does not include any authentication functionality. There are a few parts of the code where I simulate a logged-in user to test the authorization service -- please ignore this for now. I plan to implement a JWT middleware later, which will handle the authentication

Some of my inspirations have been these remarkable articles:
- https://github.com/Orphail/laravel-ddd
- https://www.hibit.dev/posts/43/domain-driven-design-with-laravel-9
- https://martinjoo.dev/blog

I primarily started by following Dani Martinez's Orphail repository, which has been a great help in visualizing how Domain-Driven Design (DDD) should be implemented.
Howerver, I have made a few changes to the folder structure and incorporated additional elements that I believe will be more useful in a real-world context.

And lastly, I would really appreciate any advice on how to improve this project, even if it's about the smallest issues, coding conventions, or areas where I'm not following best practices. I want to learn and improve, so all feedback is valuable to me.

You can contact me via email at vinh0809it@gmail.com

## 📗 Installation
1. ```composer install```
2. ```cp .env.example .env```
3. ```php artisan key:generate```
4. Set database connection in the ```.env```
5. ```php artisan migrate```
6. ```php artisan db:seed```
7. ```php artisan test```
8. ```php artisan serve```

## 📁 Structure particularities

I find this structure clearer, so I decided to stick with it.
For more information, you could visit Orphail repository.

User domain is just a mocked example
You could focus on the SalaryHistory Domain, that follows more strict with DDD principles than User Domain

```
├── User
│   ├── Domain
│   ├── Application
│   ├── Presentation
│   └── Infrastructure
├── SalaryHistory
│   ├── Domain
│   ├── Application
│   ├── Presentation
│   └── Infrastructure
```

