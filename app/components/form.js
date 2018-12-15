module.exports = {

    data () {
        return  _.merge({
            status: 0,
            form: null,
            formData: new FormData
        }, this.$options.form);
    },


    methods: {

        save () {

            this.formData.append('data', JSON.stringify({
                mail: this.mail,
                adresses: this.adresses,
                values: this.values
            }));

            this.status = 1;

            this.$http.post('api/form', this.formData).then(function (res) {

                console.log(res);

                this.$notify('Success.');

                this.status = 2;

            }, function (res) {
                this.$notify(res.data, 'danger');
                this.status = 3;
            });
        },

        files(name) {
            _.each(this.$els[name].files, (file) => {
                this.formData.append(name+'[]', file, file.name);
            });
        },

        components: {
            loader: {
                template:  `<svg class="pk-loader" width="30" height="30" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg">
                                <g><circle cx="0" cy="0" r="13" fill="none" stroke-width="1"/></g>
                            </svg>`
            }
        }
    }
}