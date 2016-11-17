var path = require('path'),
    webpack = require('webpack');

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
        loaders: [
            {
                test: /\.js$/,
                loader: 'babel',
                exclude: /node_modules/
            }, {
                test: /\.json$/,
                loader: 'json'
            }
        ]
    },
    plugins: [
        // always uglify so we're actually testing real world code in the browser
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            }
        })
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.js'
        }
    }
};