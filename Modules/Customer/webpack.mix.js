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

mix.js("Resources/js", "/public/js/customer/app.js")
    .vue({ version: 2 })
    .postCss("Resources/css/app.css", "/public/css/customer/app.css", [
        require("tailwindcss")
    ]);

mix.copy(
    __dirname + "/public/css/customer/app.css",
    "../../public/css/customer/app.css"
);

mix.copy(
    __dirname + "/public/js/customer/app.js",
    "../../public/js/customer/app.js"
);
