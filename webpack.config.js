const path = require('path')
const {CleanWebpackPlugin} = require('clean-webpack-plugin')
const TerserWebpackPlugin = require('terser-webpack-plugin')

const isDev = process.env.NODE_ENV === 'development'
const isProd = !isDev


const optimization = () => {
    let config = {}

    if(isProd) {
        config.minimizer = [
            new TerserWebpackPlugin()
        ]
    }
    return config
}


module.exports = {
    context: path.resolve(__dirname, 'src'),
    mode: 'development',
    entry: {
        main: ['@babel/polyfill', './index.js']
    },
    output: {
        filename: `[name].js`,
        path: path.resolve(__dirname, 'dist')
    },
    resolve: {
        extensions: ['.js'],
        alias: {
            '@': path.resolve(__dirname, 'src')
        }
    },
    externals: {
        jquery: 'jQuery',
    },
    optimization: optimization(),
    devtool: isDev ? 'source-map' : '',
    plugins: [
      new CleanWebpackPlugin(),
    ],
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: 'babel-loader',
                        options: {
                            presets: [
                                '@babel/preset-env',
                            ]
                        }
                    }
                ]
            }
        ]
    }
}
