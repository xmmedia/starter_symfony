<template>
    <span>
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
                <li v-for="(href, anchor) in items">
                    <a v-bind:href="href" class="sidebar_nav-link sidebar_nav-submenu_link">{{ anchor }}</a>
                </li>
            </ul>
        </div>
    </span>
</template>

<script>
import { mapState } from 'vuex'

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
        ...mapState({
            mobileMenuOpen: state => state.adminMenu.mobileMenuOpen
        })
    },
    data() {
        return {
            open: false,
            bodyClass: 'sidebar_nav-submenu-open',
        }
    },
    watch: {
        open() {
            if (this.open) {
                document.body.classList.add(this.bodyClass);
            } else {
                this.close();
            }
        },
        mobileMenuOpen(mobileMenuOpen) {
            if (!mobileMenuOpen) {
                this.close();
            }
        }
    },

    mounted() {
        this.$nextTick(() => {
            document.documentElement.addEventListener('click', this.htmlClick);
        });
    },
    methods: {
        htmlClick() {
            this.open = false;
            this.$store.dispatch('mobileMenuOpen', false);
        },
        close() {
            this.open = false;
            document.body.classList.remove(this.bodyClass);
        }
    }
}
</script>