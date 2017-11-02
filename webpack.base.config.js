'use strict';
// Base configuration of Encore/Webpack
module.exports = function (Encore) {
    Encore
    // directory where all compiled assets will be stored
        .setOutputPath('html/build/')

        // what's the public path to this directory (relative to your project's document root dir)
        .setPublicPath('/build')

        // empty the outputPath dir before each build
        .cleanupOutputBeforeBuild()

        // will output as build/admin.js and similar
        .addEntry('admin', './html/js/src/admin.js')
        .addEntry('public', './html/js/src/public.js')

        // allow sass/scss files to be processed
        .enableSassLoader(function(sassOptions) {}, {
            // see: http://symfony.com/doc/current/frontend/encore/bootstrap.html#importing-bootstrap-sass
            resolveUrlLoader: false
        })
        .enablePostCssLoader()
        // allow .vue files to be processed
        .enableVueLoader()

        .enableSourceMaps(true)
};