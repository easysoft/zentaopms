$(function()
{
    $('#top-submit').click(function()
    {
        $(this).addClass('disabled');
        $('form').submit();
    })
})
var app = new Vue({
    el: '#apiApp',
    data: {
        header: [],
        queryP: [],
        body: "",
        response: "",
        params: "",
        defaultHeader: {field: '', required: '', desc: ''},
        attr: [],
        attrType: 'formData',
        api
    },
    created() {
        this.header.push({...this.defaultHeader})
        this.queryP.push({...this.defaultHeader})
        if(api) {
            if(api.params.header && api.params.header.length > 0) this.header = api.params.header
            if(api.params.query && api.params.query.length > 0) this.queryP = api.params.query
            if(api.params.params && api.params.params.length > 0) this.attr = api.params.params
            if(api.params.paramsType) this.attrType = api.params.paramsType
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
        },
        attrType: {
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
        changeType(val) {
            this.attrType = val
        },
        changeRes(val) {
            val = this.filterParams(val)
            this.response = JSON.stringify(val)
        },
        setParams() {
            const header = this.filterParams(this.header)
            const body = this.filterParams(this.body)
            const queryP = this.filterParams(this.queryP)
            const params = {
                header: header,
                params: body,
                paramsType: this.attrType,
                query: queryP,
            }
            this.params = JSON.stringify(params);
        },
        filterParams(data) {
            const res = []
            if(data && data.length > 0) {
                data.forEach(item => {
                    if(item.field && item.field.length > 0) {
                        res.push(item)
                    }
                })
            }
            return res
        },
        del(data, key) {
            if(data.length <= 1) {
                return
            }
            data.splice(key, 1)
        },
        add(data, key, t) {
            if(t == "header" || t == 'query') {
                data.splice(key + 1, 0, {...this.defaultHeader})
            }
        }
    }
})
