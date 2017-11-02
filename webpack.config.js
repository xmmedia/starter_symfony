'use strict';
const Encore = require('./webpack.base.config');
const webpackCustomize = require('./webpack.customize');

// export the final configuration
let config = Encore.getWebpackConfig();

webpackCustomize(config);

if (Encore.isProduction()) {
    config.devtool = 'source-map';
}

module.exports = config;