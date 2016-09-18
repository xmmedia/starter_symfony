import modal from '../common/modal';

export default {
    template: `
    <span>
        <form name="form" method="post" action="{{ action }}" @click.prevent="confirm">
            <input type="hidden" name="_method" value="DELETE">
            <button class="button-as_link form-action_link">Delete</button>
            <input type="hidden" id="form__token" name="form[_token]" value="{{ csrfToken }}">
        </form>
        
        <modal :show.sync="showModal">
            <p slot="content">Are you sure you want to delete this {{ recordDesc }}? This cannot be undone.</p>
            <div slot="button">
                <form name="form" method="post" action="{{ action }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button>Delete</button>
                    <input type="hidden" id="form__token" name="form[_token]" value="{{ csrfToken }}">
                </form>
            </div>
        </modal>
    </span>`,

    props: {
        action: {
            type: String,
            required: true
        },
        recordDesc: {
            type: String,
            required: true
        },
        csrfToken: {
            type: String,
            required: true
        }
    },
    data: function() {
        return {
            showModal: false,
        }
    },

    components: {
        'modal': modal,
    },

    methods: {
        confirm: function() {
            this.showModal = true;
        }
    }
}