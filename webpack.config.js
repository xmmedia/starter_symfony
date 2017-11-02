'use strict';
// This is for dev, watch and build (production)
const Encore = require('@symfony/webpack-encore');
const encoreConfigure = require('./webpack.base.config');
const webpackCustomize = require('./webpack.customize');

encoreConfigure(Encore);

// always create hashed filenames (e.g. public.a1b2c3.css)
Encore.enableVersioning(true);

// export the final configuration
let config = Encore.getWebpackConfig();

webpackCustomize(config);

if (Encore.isProduction()) {
    config.devtool = 'source-map';
}

module.exports = config;