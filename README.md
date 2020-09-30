# NOTES

`bin/console debug:config api_platform`

`bin/console cofing:dump api_platform` pour avoir toutes les configurations possibles

##### password encoder

`bin/console security:encode`

`bin/console debug:autowiring pass`

##### Tests

`composer require test --dev`

`php bin/phpuni`

`bin/console doctrine:database:create --env=test`

`bin/console doctrine:schema:create --env=test`

### Lifecycle of an Incoming Request

When you send data to an endpoint API platform does the following things in this order. 
- First, it deserializes the JSON into whatever resource object we're working with - like a CheeseListing object.
- Second, it applies the security access controls.
- And third it applies our validation rules.

#### data persister

After deserializing the data into a User object, running security checks and executing validation, API Platform finally says:

To figure out how to save the object, it loops over all of its data persisters... so... really... just one at this point... and asks:

    Hi data persister! Do you know how to "save" this object?

Because our two API resources - User and CheeseListing are both Doctrine entities, the Doctrine data persister says:

    Oh yea, I totally do know how to save that!

And then it happily calls persist() and flush() on the entity manager.

This... is awesome. Why? Because if you want to hook into the "saving" process... or if you ever create an API Resource class that is not stored in Doctrine, you can do that beautifully with a custom data persister.

#### Context Builder & Service Decoration

When you make a GET request for a collection of users or a single user, API Platform will use the same normalization group: user:read. This means the response will always contain the email, username and cheeseListings fields.

Now we need to do something smarter: we need to be able to also normalize using another group - admin:read - but only if the authenticated user has ROLE_ADMIN. The key to doing this is something called a "context builder".

Remember: when API Platform, or really, when Symfony's serializer goes through its normalization or denormalization process, it has something called a "context", which is a fancy word for "options that are passed to the serializer". The most common "option", or "context" is groups. The context is normally hardcoded via annotations but we can also tweak it dynamically.

#### Custom Normalizer

`php bin/console make:serializer:normalizer`

#### Custom Validator

`bin/console make:validator`

#### Entity Listener

- API Platform event listener
- API Platform data persister
- Doctrine event listener.

The first two - an API Platform event listener or data persister - have the same possible downside: the owner would only be automatically set when a CheeseListing is created through the API. Depending on what you're trying to accomplish, that might be exactly what you want - you may want this magic to only affect your API operations.
So, instead of making this feature only work for our API endpoints, let's use a Doctrine event listener and make it work everywhere.

#### composer

connaitre la version d'un paquet `composer show api-platform/core`

# API Platform Tutorial

Well hi there! This repository holds the code and script
for the [API Platform](https://symfonycasts.com/screencast/api-platform) course on SymfonyCasts.

## Setup

If you've just downloaded the code, congratulations!!

To get it working, follow these steps:

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

You may alternatively need to run `php composer.phar install`, depending
on how you installed Composer.

**Configure the .env (or .env.local) File**

Open the `.env` file and make any adjustments you need - specifically
`DATABASE_URL`. Or, if you want, you can create a `.env.local` file
and *override* any configuration you need there (instead of changing
`.env` directly).

**Setup the Database**

Again, make sure `.env` is setup for your computer. Then, create
the database & tables!

```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

If you get an error that the database exists, that should
be ok. But if you have problems, completely drop the
database (`doctrine:database:drop --force`) and try again.

**Compiling Webpack Encore Assets**

This tutorial uses [Webpack Encore](https://symfonycasts.com/encore),
which isn't important to understand what's going on, but *is* important
to get our app running. We'll also do a little bit of JavaScript coding
to talk to our API.

Make sure to install Node and also [yarn](https://yarnpkg.com).
Then run:

```
yarn install
yarn encore dev --watch
```

**Start the built-in web server**

You can use Nginx or Apache, but Symfony's local web server
works even better.

To install the Symfony local web server, follow
"Downloading the Symfony client" instructions found
here: https://symfony.com/download - you only need to do this
once on your system.

Then, to start the web server, open a terminal, move into the
project, and run:

```
symfony serve
```

(If this is your first time using this command, you may see an
error that you need to run `symfony server:ca:install` first).

Now check out the site at `https://localhost:8000`

Have fun!

## Have Ideas, Feedback or an Issue?

If you have suggestions or questions, please feel free to
open an issue on this repository or comment on the course
itself. We're watching both :).

## Thanks!

And as always, thanks so much for your support and letting
us do what we love!

<3 Your friends at SymfonyCasts
