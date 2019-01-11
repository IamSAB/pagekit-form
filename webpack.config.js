
module.exports = [

    {
        entry: {
            "forms": "./app/views/forms.js",
        },
        output: {
            filename: "./app/bundle/[name].js"
        },
        module: {
            loaders: [
                { test: /\.vue$/, loader: "vue-loader" },
                { test: /\.js$/, loader: "babel", query: {presets: ['es2015',]}}
            ]
        }
    }

];