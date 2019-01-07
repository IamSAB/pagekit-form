import Send from './send.vue';

export default {

    data () {
        return  {
            status: 0,
            form: null,
            values: this.$options.values,
            files: {}
        };
    },

    methods: {

        addFiles(name) {
            this.files[name] = this.$els[name].files;
        }
    },

    events: {

        send (mail) {

            this.status = 1; // sending

            let data = _.merge({
                index: this.$options.index,
                node: this.$options.node,
                mail: mail,
                values: this.values
            }, this.files);

            this.$http.post('api/form', data).then((res) => {
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