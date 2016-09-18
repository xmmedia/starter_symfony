export default {
    template : '<div style="height: 0; width: 0; position: absolute; visibility: hidden;">{{{ svg }}}</div>',

    props : ['src'],
    data : function() {
        return {
            'svg' : ''
        };
    },

    ready : function() {
        // load the SVG file
        this.$http.get(this.src).then((response) => {
            this.svg = response.data;
        });
    }
}