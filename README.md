<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Laravel Breeze API template

This repository is a template for Laravel projects based on Sail environment to integrate with the Sanctum authentication.

You can use this project to integrate with one of these frontend applications:

-   [Breeze Next](https://github.com/laravel/breeze-next/) (React/Next)
-   [Breeze Nuxt](https://github.com/manchenkoff/breeze-nuxt) (Vue/Nuxt)

## Prerequisites

To work with this project you will also need to install the following software:

-   [Git](https://git-scm.com/)
-   [Docker](https://docker.com/)
-   [Justfile](https://just.systems/)

## Features

-   Laravel 12
-   Breeze API with Sanctum
-   Laravel Pint code formatter
-   Larastan static analysis rules
-   IDE helper for Laravel (Stubs generation)

## Installation

1. Clone the repository:

```bash
git clone https://github.com/manchenkoff/breeze-api
```

2. Build the project and install dependencies:

```bash
just build
```

3. Start the project:

```bash
just start
```

Once the project is started, you can access it at [http://localhost](http://localhost).

## Development

To get more details about available commands in `justfile`, run the following command:

```bash
just help
```

To auto-format your code use `just fmt` command and also `just lint` to check the code quality by running Larastan checks.

## Production

**Environment**

To make sure that Laravel Sanctum will work on your production instance, make sure that you defined properly the following environment variables:

```dotenv
APP_KEY=base64:your_key_here    # Generate a new key using `php artisan key:generate --show`
FRONTEND_URL=https://domain.com # Your frontend Nuxt application URL
SESSION_DOMAIN=.domain.com      # Your domain should start with a dot to support all subdomains like www.* or frontend.*
```

_💡 Keep in mind, that `SESSION_DOMAIN` is not applicable for `localhost` and should not be used during development with the value other than `null`._

**Multiple apps**

If you have multiple frontend applications (e.g. public and admin apps), you can define the `SANCTUM_STATEFUL_DOMAINS` environment variable to allow multiple domains to access the same session.

```dotenv
SANCTUM_STATEFUL_DOMAINS=domain.com,backoffice.domain.com,admin.domain.com
```


