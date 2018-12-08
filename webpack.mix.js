const mix = require('laravel-mix');
require('laravel-mix-purgecss');
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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .purgeCss({
        // enabled: true, // <- is enabled by default in prod mode. Use this to debug in dev mode
        //
        // If you encounter css that is removed but needed, then whitelist it according to the docs:
        // https://www.purgecss.com/whitelisting
        //
        // whitelist: []
    });
