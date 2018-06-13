const path = require('path');

module.exports = {
  mode: 'production',
  watch: true,
  // watchOptions: {
  //     aggregateTimeout: 300, // The default
  //     poll: 1000 // Check for changes every second
  // },
  entry: './assets/js/actions.js',
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, 'assets/js/dist')
  }
};


