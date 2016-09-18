export default {
    // only have the <span> because we need a single root el for Vue
    template: `<span>
        <button 
            class="sidebar_nav-link sidebar_nav-submenu_arrow" 
            @click.stop="open = !open" 
            v-bind:class="{ 'sidebar_nav-submenu_arrow-open' : open }">
            <svg><use xlink:href="#gt"></use></svg>
        </button>
        <div 
            class="sidebar_nav-submenu-wrap" 
            v-bind:class="{ 'sidebar_nav-submenu-wrap-open' : open }">
            <div class="sidebar_nav-submenu_header">{{ name }}</div>
            <ul class="sidebar_nav-submenu">
                <li v-for="(anchor, href) in items">
                    <a href="{{ href }}" class="sidebar_nav-link sidebar_nav-submenu_link">{{ anchor }}</a>
                </li>
            </ul>
        </div>
    </span>`,

    data: function() {
        return {
            open: false,
            bodyClass: 'sidebar_nav-submenu-open',
        }
    },
    props: {
        name: {
            type: String,
            required: true,
        },
        items: {
            type: Object,
            required: true,
        }
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

    ready: function() {
        document.documentElement.addEventListener('click', this.htmlClick);
    },
    methods: {
        htmlClick: function() {
            this.open = false;
        }
    }
}