const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
module.exports = {
  mode: 'development',
  entry: {
   'js/app' : './src/js/app.js',
    'js/inicio' : './src/js/inicio.js',
    'js/instructor/index': './src/js/instructor/index.js',
    'js/falta/index': './src/js/falta/index.js',
    'js/alumno/index': './src/js/alumno/index.js',
    'js/alugrado/index': './src/js/alugrado/index.js',
    'js/sancion/index': './src/js/sancion/index.js',
    'js/demerito/index': './src/js/demerito/index.js',
    'js/estadisticas/index': './src/js/estadisticas/index.js',
    'js/arresto/index': './src/js/arresto/index.js',
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'public/build')
  },
  plugins: [
    new MiniCssExtractPlugin({
        filename: 'styles.css'
    })
  ],
  module: {
    rules: [
      {
        test: /\.(c|sc|sa)ss$/,
        use: [
            {
                loader: MiniCssExtractPlugin.loader
            },
            'css-loader',
            'sass-loader'
        ]
      },
      {
        test: /\.(png|svg|jpe?g|gif)$/,
        type: 'asset/resource',
      },
    ]
  }
};