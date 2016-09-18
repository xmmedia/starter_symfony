var path = require('path'),
    webpack = require('webpack'),
    UglifyJsPlugin = require('webpack/lib/optimize/UglifyJsPlugin'),
    CommonsChunkPlugin = require('webpack/lib/optimize/CommonsChunkPlugin');

module.exports = {
    entry: {
        admin : './html/js/src/admin.js',
        public : './html/js/src/public.js'
    },
    output: {
        path: path.resolve(__dirname, './html/js'),
        filename: '[name].min.js'
    },
    module: {
        loaders: [{
            test: /\.js$/,
            exclude: /node_modules/,
            loader: 'babel'
        }, {
            test: /\.json$/,
            loader: 'json'
        }]
    },
    plugins: [
        // always uglify so we're actually testing real world code in the browser
        new UglifyJsPlugin({
            compress: {
                warnings: false
            }
        })
    ]
};