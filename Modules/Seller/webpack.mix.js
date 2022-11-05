const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("Resources/js", "/public/js/seller/app.js")
    .vue({ version: 2 })
    .postCss("Resources/css/app.css", "/public/css/seller/app.css", [
        require("tailwindcss")
    ]);

mix.copy(
    __dirname + "/public/css/seller/app.css",
    "../../public/css/seller/app.css"
);

mix.copy(
    __dirname + "/public/js/seller/app.js",
    "../../public/js/seller/app.js"
);
