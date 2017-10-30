<template>
    <a href="" @click.stop.prevent="toggleMenu">Menu</a>
</template>

<script>
import { mapState } from 'vuex';

export default {
    computed: {
        ...mapState('adminMenu', {
            open: 'mobileMenuIsOpen',
        }),
    },

    mounted() {
        this.$nextTick(() => {
            this.setContentHeight();
            window.addEventListener('resize', this.windowResize);
        });
    },

    methods: {
        toggleMenu () {
            if (this.open) {
                this.$store.dispatch('adminMenu/closeMobileMenu');
            } else {
                this.$store.dispatch('adminMenu/openMobileMenu');
            }
        },
        windowResize() {
            this.$store.dispatch('adminMenu/closeMobileMenu');

            this.setContentHeight();
        },
        setContentHeight() {
            document.querySelectorAll('.js-content-wrap')[0]
                .style.minHeight = this.$root.getWindowHeight()+'px';
        }
    }
}
</script>