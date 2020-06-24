const path = require('path');
const merge = require('webpack-merge');
const TerserPlugin = require('terser-webpack-plugin');
const common = require('./webpack.common');

module.exports = merge(common, {
  mode: 'production',
  output: {
    path: path.resolve(__dirname),
    filename: '[name].js',
  },
  optimization: {
    minimizer: [
      new TerserPlugin(),
    ],
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: [
              '@wordpress/default',
              ['@babel/preset-env', {
                targets: {
                  // The % refers to the global coverage of users from browserslist
                  browsers: ['>2%', 'not ie 11', 'not op_mini all'],
                },
              }],
            ],
            plugins: [
              [
                '@babel/transform-react-jsx',
                { pragma: 'wp.element.createElement' },
              ],
              ['transform-react-remove-prop-types',
                {
                  mode: 'wrap',
                  ignoreFilenames: ['node_modules'],
                },
              ],
            ],
          },
        },
      },
    ],
  },
});
