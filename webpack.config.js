const { resolve }= require('path');
const webpack = require('webpack');

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
        path: resolve(__dirname, './html/js'),
        filename: '[name].min.js'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: [
                    'babel-loader'
                ]
            }
        ]
    },
    plugins: [
        // always uglify so we're actually testing real world code in the browser
        new webpack.optimize.UglifyJsPlugin({
            sourceMap: true,
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