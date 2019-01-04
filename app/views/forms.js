import Form from '../components/form';

Vue.ready(function () {
    const data = window.$sabform;
    _.each(data.forms, (values, i) => {
        new Vue({
            name: data.prefix+i,
            el: '#'+data.prefix+i,
            index: i,
            node: data.node,
            values: values,
            extends: Form
        })
    });
});