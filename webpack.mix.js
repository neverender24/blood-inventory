let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
   .extract(['jquery', 'bootstrap'], 'public/js/vendor.js')
   .extract(['vue', 'vuex'], 'public/js/vue.js')
   .extract(['axios', 'lodash', 'vuelidate', 'vue-toasted'], 'public/js/packages.js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .version();
