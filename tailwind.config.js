const defaultTheme = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');

module.exports = {
  content: [
    './node_modules/flowbite/**/*.js', // Include Flowbite classes
    "./resources/**/*.js",  //tira
    "./resources/**/*.vue", //tira
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './node_modules/flowbite/**/*.js', // Include Flowbite classes
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [
    forms,
    //require('flowbite/plugin'), // Add Flowbite as a plugin
  ],
};