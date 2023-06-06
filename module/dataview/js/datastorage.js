function initStorage(obj, debug = false)
{
    var dataStorage =
    {
        clone: function(key)
        {
            if(typeof this['_' + key] != 'object') return this['_' + key]
            return JSON.parse(JSON.stringify(this['_' + key]));
        },
        addProperty: function(key, value)
        {
            this[key] =
            {
                get: function()
                {
                    if(debug)
                    {
                        console.groupCollapsed('Get %c' + key, 'color: #3785ff');
                        console.log(this['_' + key]);
                        console.trace();
                        console.groupEnd();
                    }
                    return this['_' + key];
                },
                set: function(value)
                {
                    if(debug)
                    {
                        console.groupCollapsed('Set %c' + key, 'color: #ff6970');
                        console.log('Before', this['_' + key]);
                        console.log('Value', value);
                        console.trace();
                        console.groupEnd();
                    }
                    this['_' + key] = value;
                }
            }
        }
    };
    for(let key in obj)
    {
        dataStorage['_' + key] = obj[key];

        Object.defineProperty(dataStorage, key,
        {
            get: function()
            {
                if(debug)
                {
                    console.groupCollapsed('Get %c' + key, 'color: #3785ff');
                    console.log(this.clone(key));
                    console.trace();
                    console.groupEnd();
                }
                return this.clone(key);
            },
            set: function(value)
            {
                if(debug)
                {
                    console.groupCollapsed('Set %c' + key, 'color: #ff6970');
                    console.log('Before', this.clone(key));
                    console.log('Value', value);
                    console.trace();
                    console.groupEnd();
                }
                this['_' + key] = value;
            }
        });
    }

    return dataStorage;
}
