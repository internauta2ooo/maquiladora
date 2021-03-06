const mix = require("laravel-mix");
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
mix.browserSync("http://localhost:80/maquiladora/public");

mix.js("resources/js/app.js", "public/js")
    // .eslint({
    //     fix: true,
    //     extensions: ["js"]
    //     //...
    // })
    .sass("resources/sass/app.scss", "public/css");
// mix.webpackConfig({
//     entry: "./node_modules/laravel-mix/src/index.js",
//     module: {
//         rules: [
//             {
//                 enforce: "pre",
//                 test: /\.(js|vue)$/,
//                 loader: "eslint-loader",
//                 exclude: /node_modules/
//             }
//         ]
//     }
// });
