var app = new Vue({
    el: '#apiApp',
    data: {
        header: [],
        queryP: [],
        body: [],
        params: "",
        response: "",
        defaultHeader: { field: '', required: '', desc: '' },
    },
    created() {
        this.header.push({...this.defaultHeader});
        this.queryP.push({...this.defaultHeader});
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
                this.setParams();
            },
            deep: true
        },
        body: {
            handler() {
                this.setParams();
            },
            deep: true
        }
    },
    methods: {
        changeAttr(val) {
            this.body = val;
        },
        changeRes(val) {
            this.response = JSON.stringify(val);
        },
        setParams() {
            const params = {
                header: this.header,
                params: this.body,
                query: this.queryP,
            }
            this.params = JSON.stringify(params);
        },
        del(data, key) {
            if (data.length <= 1) {
                return;
            }
            data.splice(key, 1);
        },
        add(data, key, t) {
            if (t == "header" || t == 'query') {
                data.splice(key+1, 0, this.defaultHeader);
            }
        }
    }
})
