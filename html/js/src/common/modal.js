// @todo should we wrap this so it doesn't end up as a fragment
export default {
    template: `<div class="md-modal" v-bind:class="{ 'md-modal-show' : show }">
        <div class="md-content">
            <a href="" class="md_close_x" @click.prevent="show = false">x</a>
            <div class="js-md-content_wrap">
                <slot name="content"></slot>
            </div>
            <div class="md-button_wrap js-md-button_wrap">
                <slot name="button"></slot>
            </div>
        </div>
    </div>
    <div class="md-overlay" @click="show = false"></div>`,

    props: {
        show: {
            type: Boolean,
            required: true,
            twoWay: true
        }
    }
}