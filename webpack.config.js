
module.exports = [

    {
        entry: {
            "site-recaptcha": "./app/components/site-recaptcha.vue",
            "forms": "./app/views/forms.js",
        },
        output: {
            filename: "./app/bundle/[name].js"
        },
        module: {
            loaders: [
                { test: /\.vue$/, loader: "vue" },
                { test: /\.js/, loader: "babel", query: {presets: ['es2015',],},},
            ]
        }
    }

];