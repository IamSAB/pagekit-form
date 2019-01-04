import Send from './send.vue';

export default {

    data () {
        return  {
            status: 0,
            form: null,
            formData: new FormData,
            values: this.$options.values
        };
    },

    methods: {

        files(name) {
            _.each(this.$els[name].files, (file) => {
                this.formData.append(name+'[]', file, file.name);
            });
        }
    },

    events: {

        send (mail) {

            this.status = 1; // sending

            this.formData.append('data', JSON.stringify({
                index: this.$options.index,
                node: this.$options.node,
                mail: mail,
                values: this.values
            }));

            this.$http.post('api/form', this.formData).then((res) => {
                this.$notify(this.$trans('Successfully send mail.'));
                this.status = 2; // success
            }, (res) => {
                this.$notify(this.$trans('Unable to send mail.'), 'danger');
                this.status = 3; // error
            });
        }

    },

    components: {
        Send
    }
}