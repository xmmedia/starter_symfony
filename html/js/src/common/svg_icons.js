import axios from '../common/axios';

// for loading SVG icons
export default {
    template : `<div style="height: 0; width: 0; position: absolute; visibility: hidden;" v-html="svg"></div>`,

    props : ['src'],
    data() {
        return {
            svg : ''
        };
    },

    mounted() {
        let self = this;

        axios.get(this.src)
            .then((response) => {
                self.svg = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
    }
}