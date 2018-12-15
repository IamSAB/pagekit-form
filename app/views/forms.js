const Form = require('../components/form.js');

Vue.ready(function () {
    _.each(window.$forms, (form, id) => {
        new Vue({
            name: id,
            form: form,
            el: '#'+id,
            extends: Form
        })
    });
});