
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
                { test: /\.vue$/, loader: "vue" }
            ]
        }
    }

];