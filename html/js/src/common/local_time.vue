<template>
    <time :datetime="datetime">{{ displayTime }}</time>
</template>

<script>
export default {
    props: {
        datetime: {
            type: String,
            required: true
        },
        format: {
            type: String,
            default: 'YYYY-MM-DD HH:mm'
        },
        locale: {
            type: String,
            default: 'en-ca',
        }
    },
    data () {
        return {
            displayTime: '',
        }
    },

    mounted () {
        import('moment-timezone').then(this.setDisplayTime);
    },

    methods: {
        setDisplayTime (moment) {
            moment.locale(this.locale);

            this.displayTime = moment.tz(this.datetime, moment.tz.guess())
                .format(this.format);
        }
    }
}
</script>