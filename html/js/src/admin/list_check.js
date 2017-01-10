import axios from '../common/axios';

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
            let self = this;
            let originalVal = this.isChecked;

            this.isChecked = !this.isChecked;

            axios.post(this.href)
                .then((response) => {
                    self.isChecked = response.data.is_checked
                }).catch(() => {
                    self.isChecked = originalVal;
                });
        }
    }
}