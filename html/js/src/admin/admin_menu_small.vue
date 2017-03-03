<template>
    <a href="" @click.stop.prevent="open = true">Menu</a>
</template>

<script>
export default {
    data() {
        return {
            open: false,
            bodyClass: 'sidebar_nav-visible',
        };
    },

    watch: {
        open() {
            if (this.open) {
                document.body.classList.add(this.bodyClass);
            } else {
                document.body.classList.remove(this.bodyClass);
            }
        }
    },

    mounted() {
        this.$nextTick(() => {
            this.setContentHeight();
            window.addEventListener('resize', this.windowResize);
        });
    },
    methods: {
        windowResize() {
            this.open = false;

            this.setContentHeight();
        },
        setContentHeight() {
            document.querySelectorAll('.js-content-wrap')[0]
                .style.minHeight = this.getWindowHeight() + 'px';
        },
        getWindowHeight() {
            let w = window,
                d = document,
                e = d.documentElement,
                g = d.body;

            return w.innerHeight|| e.clientHeight|| g.clientHeight;
        }
    }
}
</script>