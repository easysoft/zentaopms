new Vue({
    el: '#app',
    data: function () {
        return {
            attr: '',
            struct: {
                ...struct,
                attribute: struct.attribute
            }
        }
    },
    methods: {
        changeAttr(val) {
            this.attr = JSON.stringify(val)
        },
    }
})
