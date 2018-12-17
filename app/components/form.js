import invisibleRecaptcha from './invisible-recaptcha.vue';

export default {

    data () {
        return  _.merge({
            status: 0,
            form: null,
            formData: new FormData,
        }, this.$options.form);
    },

    methods: {

        submit () {
            if (this.$pagekit.recaptcha) this.$refs.recaptcha.execute();
            else this.save();
        },

        save () {

            this.formData.append('data', JSON.stringify({
                mail: this.mail,
                adresses: this.adresses,
                values: this.values
            }));

            this.status = 1;

            this.$http.post('api/form', this.formData).then(function (res) {

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

        onCaptchaVerified (recaptchaToken) {
            this.$refs.recaptcha.reset();
            this.formData.append('recaptcha', recaptchaToken);
            this.save();
        },

        onCaptchaExpired () {
            this.$refs.recaptcha.reset();
        },

        onCaptchaError (error) {
            this.$notify(error);
        },

        render(id)
        {
            console.log(id);
        }
    },

    components: {
        invisibleRecaptcha
    }
}