import Form from '../components/form';

function getFormData(fd, data, prevKey = '') {
    _.each(data, (value, key) => {
        // set new key
        if (prevKey) {
            key = `${prevKey}[${key}]`;
        }
        if (value instanceof FileList) {
            _.each(value, (file) => {
                fd.append(key+'[]', file, file.name);
            });
        }
        if (_.isObject(value)) {
            return getFormData(fd, value, key);
        }
        else {
            fd.append(key, value);
        }
    });
    return fd;
}

Vue.http.interceptors.push(() => {
    return {
        request (request) {
            if (request.url == 'api/form') {
                request.data = getFormData(new FormData, request.data);
            }
            return request;
        }
    }
});


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