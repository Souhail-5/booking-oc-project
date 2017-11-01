[![SensioLabsInsight](https://insight.sensiolabs.com/projects/79527127-8310-4413-9d62-6c24fea50280/big.png)](https://insight.sensiolabs.com/projects/79527127-8310-4413-9d62-6c24fea50280)

# Booking OC Project

A Symfony ticketing system for The Louvre. This project was done as part of a training ([OpenClassrooms](https://openclassrooms.com/)).

**Instructions to follow**:
The Louvre museum has commissioned you for an ambitious project: to create a new system for booking and managing tickets online to reduce long queues and take advantage of the growing use of smartphones.
You will develop in PHP + Symfony ; provide unit and functional tests (5 or more) ; implement stripe and send tickets by email.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Technical prerequisites

```
PHP 7+
MySQL 5.6+
Node.js & npm & yarn (are needed because we use Webpack Encore for compliling CSS + JS files and for using Browsersync)
Composer
```

### How to install?

**STEP 1** => Download the whole project zip and unizp it in your computer.

**STEP 2** => Make sure you have last composer version and install all dependencies that are listed in composer.json:

```
// In your terminal
cd project-folder
composer install
// Enter your parameters
php bin/console cache:clear
```

**STEP 3** => Initiate the database data:
* Install the database structure using `sh utils/fixtures.sh` from the project folder. One event and some orders are already there.

**STEP 4** => Launch:

```
// In your terminal
cd project-folder
php bin/console server:run
```

You are done!

### How to use?

When the project is installed and running, follow this steps:
* Go to `your-domain.com/`
* Click on `> Accéder à notre billetterie.`
* Choose the event you want to attend by clicking on `> Commander vos billets`
* Follow the 3 steps displayed to get your tickets!

### Attention!

Do not forget to gitignore `/app/config/parameters.yml`. Otherwise you will push all of your secret configs.

## Deployment

To compile CSS + JS changes of `/web/assets/` execute this:

```
cd project-folder
yarn run encore dev --watch
```

## Built With

* [PostCSS](http://postcss.org/) - A tool for transforming CSS with JavaScript.
* [Webpack Encore](https://symfony.com/blog/introducing-webpack-encore-for-asset-management) - It wraps Webpack, giving us a clean & powerful API for bundling JavaScript modules, pre-processing CSS & JS and compiling and minifying assets.
* [Browsersync](https://browsersync.io/) - A toolkit to get time-saving synchronised browser testing.
* [Autoprefixer](https://github.com/postcss/autoprefixer) - Add vendor prefixes to CSS rules using values from Can I Use.
* [cssnext](http://cssnext.io/) - It transforms CSS specs into more compatible CSS so you don’t need to wait for browser support.

## Contributing

(Not available for now). Please read [CONTRIBUTING.md](#) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We will use [SemVer](http://semver.org/) for versioning.

## Authors

* **Souhail** - *Initial work* - [Souhail_5](https://github.com/Souhail-5)

See also the list of [contributors](https://github.com/Souhail-5/booking-oc-project/contributors) who participated in this project.

## License

This project is licensed under the GPL3 License - see the [LICENSE](LICENSE.md) file for details.
