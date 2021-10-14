var app = new Vue({
    el: '#apiApp',
    data: {
        header: [],
        queryP: [],
        body: "",
        response: "",
        params: "",
        defaultHeader: [
            { field: '', required: '', desc: '' }
        ],
        api
    },
    created() {
        this.header.push({...this.defaultHeader})
        this.queryP.push({...this.defaultHeader})
        if (api) {
            this.header = api.params.header
            this.queryP = api.params.query
            this.setParams();
        }
    },
    watch: {
        header: {
            handler() {
                this.setParams();
            },
            deep: true
        },
        queryP: {
            handler() {
                this.setParams()
            },
            deep: true
        },
        body: {
            handler() {
                this.setParams()
            },
            deep: true
        }
    },
    methods: {
        changeAttr(val) {
            this.body = val
        },
        changeRes(val) {
            this.response = JSON.stringify(val)
        },
        setParams() {
            const params = {
                header: this.header,
                params: this.body,
                query: this.queryP,
            }
            // console.log(params)
            this.params = JSON.stringify(params);
        },
        del(data, key) {
            if (data.length <= 1) {
                return
            }
            data.splice(key, 1)
        },
        add(data, key, t) {
            if (t == "header" || t == 'query') {
                data.splice(key+1, 0, this.defaultHeader)
            }
        }
    }
})