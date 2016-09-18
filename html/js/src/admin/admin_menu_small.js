export default {
    template: `<a href="" @click.stop.prevent="open = true">Menu</a>`,

    data: function() {
        return {
            open: false,
            bodyClass: 'sidebar_nav-visible',
        };
    },

    watch: {
        open: function() {
            if (this.open) {
                document.body.classList.add(this.bodyClass);
            } else {
                document.body.classList.remove(this.bodyClass);
            }
        }
    },

    ready: function () {
        this.setContentHeight();
        window.addEventListener('resize', this.windowResize);
    },
    methods: {
        windowResize: function () {
            this.open = false;

            this.setContentHeight();
        },
        setContentHeight: function () {
            document.querySelectorAll('.js-content-wrap')[0]
                .style.minHeight = this.getWindowHeight() + 'px';
        },
        getWindowHeight: function () {
            var w = window,
                d = document,
                e = d.documentElement,
                g = d.body;

            return w.innerHeight|| e.clientHeight|| g.clientHeight;
        }
    }
}