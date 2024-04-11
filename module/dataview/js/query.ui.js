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

window.DataStorage = initStorage(
{
    fields: {},
    columns: {},
    rows: {},
    langs: langSettings ? JSON.parse(langSettings) : {},
    relatedObject: {},
    fieldSettings: Array.isArray(fieldSettings) || typeof fieldSettings != 'object' ? {} : fieldSettings,
    objectFields: objectFields
}, true);

window.query = function(callback) 
{
    DataStorage.isInsert = false;

    var pivot = DataStorage.pivot;

    var filters = '';
    if(pivot)
    {
        if(!setVarFrom()) return false;

        filters = DataStorage.pivot.filters;

        filters.forEach(function(filter, index)
        {
            if(filter.from == 'query')
            {
                var find = $('#queryFilters').find('.filter-item-' + index + ' .form-' + filter.type);
                if(find.length != 0) filter.default = find.val();
            }
        });
    }

    DataStorage.fields  = {};
    DataStorage.columns = {};
    DataStorage.rows    = [];

    $('.query').addClass('disabled');
    $('.querying').removeClass('hidden');

    $.post($.createLink('dataview', 'ajaxQuery'), {sql: $('#sql').val(), filters: filters}, function(resp)
    {
        resp = JSON.parse(resp);

        $('.query').removeClass('disabled');
        $('#querying').addClass('hidden');

        if(resp.result !== 'success')
        {
            $('#exportDataview').addClass('hidden');

            var message = resp.message.errorInfo ? resp.message.errorInfo[2] : resp.message;
            $('.error').removeClass('hidden');
            $('.error td').html(message);

            drawTable([], []);
        }
        else
        {
            $('#exportDataview').removeClass('hidden');

            DataStorage.fields        = resp.fields;
            DataStorage.columns       = resp.columns;
            DataStorage.rows          = resp.rows;
            vars                      = resp.vars;
            DataStorage.relatedObject = resp.relatedObject;

            if(resp.filters.length)
            {
                pivot.filters = resp.filters;
                DataStorage.pivot = pivot;

                resp.filters.forEach(function(filter, index)
                {
                    if(filter.from != 'query') return;
                    if(filter.type != 'date' && filter.type != 'datetime') return;

                    var find = $('#queryFilters').find('.filter-item-' + index + ' .form-' + filter.type);
                    find.val(filter.default);
                });
            }

            drawTable(resp.fields, resp.rows);

            if(typeof callback == 'function') callback();
        }
    });
}

function drawTable(fields, rows)
{
    $.post($.createLink('dataview', 'ajaxGetFieldName'), {fields: JSON.stringify(fields), fieldSettings: JSON.stringify(DataStorage.fieldSettings)}, function(resp)
    {
        resp = JSON.parse(resp);
        if(resp.result == 'success') fields = resp.fields ? resp.fields : fields;

        $('.table-empty-tip').addClass('hidden');

        if(rows.length == 0)
        {
            $('.table-empty-tip').removeClass('hidden');
            $('table.result').empty();
            return;
        }

        const options     = {cols: fields, data: rows};
        const resultTable = $('#resultTable').zui();

        if(resultTable)
        {
            resultTable.render(options);
        }
        else
        {
            new zui.DTable(document.getElementById('resultTable'), options);
        }
    });
}
