export default {
    data: function () {
        return {
            setPassword: false,
        };
    },
    watch: {
        'setPassword': function() {
            this.$refs.password.focus();
        }
    }
}