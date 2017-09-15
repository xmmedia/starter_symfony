<template>
    <span>
        <button class="sidebar_nav-link sidebar_nav-submenu_arrow"
                @click.stop="toggleMenu"
                :class="{ 'sidebar_nav-submenu_arrow-open' : open }">
            <svg><use xlink:href="#gt"></use></svg>
        </button>
        <div class="sidebar_nav-submenu-wrap"
             :class="{ 'sidebar_nav-submenu-wrap-open' : open }">
            <div class="sidebar_nav-submenu_header">{{ name }}</div>
            <ul class="sidebar_nav-submenu">
                <li v-for="(href, anchor) in items">
                    <a :href="href"
                       class="sidebar_nav-link sidebar_nav-submenu_link">{{ anchor }}</a>
                </li>
            </ul>
        </div>
    </span>
</template>

<script>
import { mapState } from 'vuex';

export default {
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

    computed: {
        ...mapState('adminMenu', {
            mobileMenuIsOpen: 'mobileMenuIsOpen',
            subNavOpen: 'subNavOpen',
        })
    },

    data() {
        return {
            id: Math.random().toString(36).substring(7),
            open: false,
        }
    },

    watch: {
        mobileMenuIsOpen (mobileMenuIsOpen) {
            if (!mobileMenuIsOpen) {
                this.close();
            }
        },
        subNavOpen (openMenuId) {
            if (openMenuId !== this.id) {
                this.close();
            }
        },
    },

    mounted() {
        this.$nextTick(() => {
            document.documentElement.addEventListener('click', this.htmlClick);
        });
    },

    methods: {
        toggleMenu () {
            this.open = !this.open;
            if (this.open) {
                this.$store.dispatch('adminMenu/subNavOpened', this.id);
            } else {
                this.$store.dispatch('adminMenu/subNavClosed');
            }
        },
        close () {
            this.open = false;
        },
        htmlClick () {
            this.close();
            this.$store.dispatch('adminMenu/closeAllMenus');
        },
    }
}
</script>