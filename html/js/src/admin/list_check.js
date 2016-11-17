export default {
    template: `<a v-bind:href="href" class="-link-no_underline" @click.prevent="change">
        <svg class="record_list-icon_wrap" v-bind:class="[ isChecked ? 'record_list-icon-green' : 'record_list-icon-grey' ]">
            <use xlink:href="#check"></use>
        </svg>
    </a>`,

    props: {
        href: {
            type: String,
            required: true,
        },
        isChecked: {
            type: Boolean,
            default: false,
        },
    },

    methods: {
        change: function () {
            var originalVal = this.isChecked;

            this.isChecked = !this.isChecked;

            this.$http.post(this.href).then(
                (response) => {
                    return response.json();
                },
                () => {
                    this.isChecked = originalVal;
                }
            ).then((json) => {
                this.isChecked = json.is_checked;
            });
        }
    }
}