<template>

    <div v-el:container></div>

</template>

<script>

    export default {

        data: () => ({
            widgetId: null,
            sitekey: window.$pagekit.recaptcha
        }),

        ready () {
            if (this.sitekey) this.init();
        },

        methods: {

            init () {
                setTimeout(() => {
                    if(typeof window.grecaptcha.render !== "function") this.init();
                    else this.render();
                }, 100);
            },

            render () {
                this.widgetId = window.grecaptcha.render(this.$els.container, {
                    sitekey: this.sitekey,
                    size: 'invisible',
                    callback: this.emitVerify,
                    'expired-callback': this.emitExpired,
                    'error-callback': this.emitError
                });
            },

            execute () {
                window.grecaptcha.execute(this.widgetId)
            },

            reset () {
                window.grecaptcha.reset(this.widgetId)
            },

            emitVerify (response) {
                this.$emit('verify', response);
            },

            emitExpired () {
                this.$emit('expired');
            },

            emitError (error) {
                this.$emit('error', error);
            }

        }

    };

</script>