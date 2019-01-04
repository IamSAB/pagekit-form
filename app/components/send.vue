<template>
    <button :class="class" type="submit" :disabled="status > 0">
        <span v-if="status == 0">{{ 'Send' | trans }}</span>
        <div v-if="status == 1">
            <svg class="loader" width="30" height="30" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg">
                <g><circle cx="0" cy="0" r="13" fill="none" stroke-width="1"/></g>
            </svg>
            <span style="margin-left: 5px;">{{ 'Sending' | trans }}</span>
        </div>
        <span v-if="status == 2">{{ 'Sended' | trans }}</span>
        <span v-if="status == 3">{{ 'Unable to send mail.' | trans }}</span>
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
        opts: Object,
        status: Number,
    },

    events: {

        prepare () {
            let data = {
                subject: this.subject,
                priority: this.priority
            }
            this.$dispatch('send', _.merge({
                subject: this.subject,
                to: this.to
            }, this.opts));
        },
    }

}
</script>

<style scoped>

loader{
    vertical-align:middle;
    -webkit-animation:loader-rotate 1.4s linear infinite;
    animation:loader-rotate 1.4s linear infinite
}
.loader>*{
    -webkit-transform:translate(15px, 15px);
    transform:translate(15px, 15px)
}
.loader>*>*{
    stroke-dasharray:82px;
    stroke-dashoffset:0;
    -webkit-animation:loader-dash 1.4s ease-in-out infinite,loader-color 5.6s ease-in-out infinite;
    animation:loader-dash 1.4s ease-in-out infinite,loader-color 5.6s ease-in-out infinite;
    stroke-linecap:round
}
@-webkit-keyframes loader-rotate{
    0%{
        -webkit-transform:rotate(0deg)
    }
    100%{
        -webkit-transform:rotate(270deg)
    }
}
@keyframes loader-rotate{
    0%{
        transform:rotate(0deg)
    }
    100%{
        transform:rotate(270deg)
    }
}
@-webkit-keyframes loader-dash{
    0%{
        stroke-dashoffset:82px
    }
    50%{
        stroke-dashoffset:20.5px;
        -webkit-transform:rotate(135deg)
    }
    100%{
        stroke-dashoffset:82px;
        -webkit-transform:rotate(450deg)
    }
}
@keyframes loader-dash{
    0%{
        stroke-dashoffset:82px
    }
    50%{
        stroke-dashoffset:20.5px;
        transform:rotate(135deg)
    }
    100%{
        stroke-dashoffset:82px;
        transform:rotate(450deg)
    }
}
@-webkit-keyframes loader-color{
    0%{
        stroke:#3a94e0
    }
    50%{
        stroke:#3a94e0
    }
    100%{
        stroke:#3a94e0
    }
}
@keyframes loader-color{
    0%{
        stroke:#3a94e0
    }
    50%{
        stroke:#3a94e0
    }
    100%{
        stroke:#3a94e0
    }
}
</style>
