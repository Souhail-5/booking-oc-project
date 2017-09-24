// webpack.config.js
var Encore = require('@symfony/webpack-encore');

Encore
    // directory where all compiled assets will be stored
    .setOutputPath('web/build/')

    // what's the public path to this directory (relative to your project's document root dir)
    .setPublicPath('/build')

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // will output as web/build/app.js
    // .addEntry('app', './assets/js/main.js')

    // will output as web/build/main.css
    .addStyleEntry('main', './web/assets/css/main.css')

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

    // create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning()

    .enablePostCssLoader((options) => {
        options.config = {
            path: 'app/config/postcss.config.js'
        };
    })
;

// fetch Encore config, then modify it!
var config = Encore.getWebpackConfig();
config.watchOptions = { poll: true, ignored: /node_modules/ };

// export the final configuration
module.exports = config;
