new Vue({
    el: '#app',
    data: function () {
        return {
            attr: ''
        }
    },
    methods: {
        changeAttr(val) {
            this.attr = JSON.stringify(val)
        }
    }
})