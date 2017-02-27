<template>
    <time :datetime="datetime">{{ displayTime }}</time>
</template>

<script>
import moment from 'moment';
import 'moment/locale/en-ca';
import 'moment-timezone';

// set the moment.js locale
moment.locale('en-ca');

export default {
    props: {
        datetime: {
            type: String,
            required: true
        },
        format: {
            type: String,
            default: 'YYYY-MM-DD HH:mm'
        }
    },

    computed: {
        displayTime : function() {
            let tz_time = moment.tz(this.datetime, moment.tz.guess());
            return tz_time.format(this.format);
        }
    }
}
</script>