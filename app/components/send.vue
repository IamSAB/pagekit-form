<template>
    <button :class="class" type="submit" :disabled="status > 0">
        <slot v-if="status == 0">
            {{ 'Send' | trans }}
        </slot>
        <slot name="sending" v-if="status == 1">
            {{ 'Sending' | trans }}
        </slot>
        <slot name="success" v-if="status == 2">
            {{ 'Sent' | trans }}
        </slot>
        <slot name="error" v-if="status == 3">
            {{ 'Unable to send mail.' | trans }}
        </slot>
    </button>
</template>

<script>

export default {

    props: {
        class: String,
        subject: {
            type: String,
        },
        to: {
            type: [String, Array],
            required: true
        },
        cc: [String, Array],
        bcc: [String, Array],
        replyTo: [String, Array],
        priority: {
            validate: (val) => {
                return val instanceof Number && val >= 1 && val <= 5;
            }
        },
        title: String,
        desc: String,
        status: {
            typ: Number,
            required: true
        },
    },

    events: {

        prepare () {

            let mail = {};
            _.each(['subject', 'to', 'cc', 'bcc', 'replyTo', 'priority', 'title', 'desc'], (key) => {
                if (this[key]) {
                    mail[key] = this[key];
                }
            });

            this.$dispatch('send', mail);
        }

    }

}
</script>
