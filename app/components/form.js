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
            if (window.$pagekit.recaptcha) this.$refs.recaptcha.execute();
            else this.save();
        },

        save () {

            this.mail.i = this.i;

            this.formData.append('data', JSON.stringify({
                mail: this.mail,
                adresses: this.adresses,
                values: this.values
            }));

            this.status = 1; // sending

            this.$http.post('api/form', this.formData).then((res) => {
                this.$notify(this.$trans('Successfully send mail.'));
                this.status = 2; // success
            }, (res) => {
                this.$notify('Unable to send mail.', 'danger');
                this.status = 3; // error
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
        }
    },

    components: {
        invisibleRecaptcha
    }
}