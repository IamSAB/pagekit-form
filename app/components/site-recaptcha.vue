<template>

    <div>

        <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
            <div data-uk-margin>
                <h2 class="uk-margin-remove">{{ 'Recaptcha' | trans }}</h2>
            </div>
            <div data-uk-margin>
                <button class="uk-button uk-button-primary" type="submit">{{ 'Save' | trans }}</button>
            </div>
        </div>

        <div class="uk-form uk-form-horizontal">

            <div class="uk-form-row">
                <label for="form-sitekey" class="uk-form-label">Site key</label>
                <div class="uk-form-controls">
                    <input id="form-sitekey" class="uk-form-width-large" name="sitekey" type="text" v-model="config.recaptcha.sitekey">
                </div>
            </div>

            <div class="uk-form-row">
                <label for="form-secretkey" class="uk-form-label">Secret key</label>
                <div class="uk-form-controls">
                    <input id="form-secretkey" class="uk-form-width-large" name="secretkey" type="text" v-model="config.recaptcha.secret">
                </div>
            </div>

        </div>

    </div>

</template>

<script>

    const settings = {

        section: {
            label: 'Recaptcha',
            priority: 100
        },

        data: () => ({
            config: window.$form
        }),

        events: {

            save () {

                this.$http.post('admin/system/settings/config', {name: 'form', config: this.config}).catch(function (res) {
                    this.$notify(res.data, 'danger');
                });

            }

        }

    };

    window.Site.components['site-recaptcha'] = settings;

    export default settings;

</script>