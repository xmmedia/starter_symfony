import modal from '../common/modal';

export default {
    template: `
    <span>
        <div>
            <a href="" @click.prevent="showModal = true" class="form-action_link">Delete</a>
        </div>
        
        <modal v-if="showModal" @close="showModal = false">
            <p slot="content">Are you sure you want to delete this {{ recordDesc }}? This cannot be undone.</p>
            <div slot="button">
                <form name="form" method="post" v-bind:action="action">
                    <input type="hidden" name="_method" value="DELETE">
                    <button>Delete</button>
                    <input type="hidden" id="form__token" name="form[_token]" v-bind:value="csrfToken">
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
    }
}