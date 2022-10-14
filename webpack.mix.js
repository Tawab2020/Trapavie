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

mix.js("resources/user/frontend/js/app.js", "public/assets/user/frontend/js/mix")
    .vue()
    .postCss(
        "resources/user/frontend/css/app.css",
        "public/assets/user/frontend/css",
        [
            //
        ]
    );

mix.js("resources/admin/js/app.js", "public/assets/admin/js")
    .vue()
    .postCss("resources/admin/css/app.css", "public/assets/admin/css", [
        //
    ]);
