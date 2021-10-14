console.log(struct)
new Vue({
    el: '#app',
    data: function () {
        return {
            attr: '',
            struct: {
                ...struct,
                attribute: JSON.parse(struct.attribute)
            }
        }
    },
    methods: {
        changeAttr(val) {
            this.attr = JSON.stringify(val)
        },
    }
})