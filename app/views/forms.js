import Form from '../components/form';

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