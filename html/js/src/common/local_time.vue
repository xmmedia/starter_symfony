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
        locale: {
            type: String,
            default: 'en-ca',
        }
    },

    computed: {
        displayTime () {
            const str = new Date(this.datetime).toISOString();

            return str.substring(0, 10)+' '+str.substring(11, 16);
        },
    },
}
</script>