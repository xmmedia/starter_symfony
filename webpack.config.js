let path = require('path'),
    webpack = require('webpack');

module.exports = {
    entry: {
        admin : [
            'babel-polyfill',
            './html/js/src/admin.js'
        ],
        public : [
            'babel-polyfill',
            './html/js/src/public.js'
        ]
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
            // see: https://github.com/vuejs/vue/blob/dev/dist/README.md
            vue: 'vue/dist/vue.common.js',
        }
    }
};