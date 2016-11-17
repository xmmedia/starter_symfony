import admin_delete from './admin_delete';

export default {
    data: function () {
        return {
            setPassword: false,
        };
    },
    components: {
        'admin-delete': admin_delete,
    },
    watch: {
        'setPassword': function() {
            this.$refs.password.focus();
        }
    }
}