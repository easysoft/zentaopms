var modal;

/**
 * Query sql.
 *
 * @param function callback
 * @access public
 * @return void
 */
function query(callback) {
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
    $('#querying').removeClass('hidden');
    $.post(createLink('dataview', 'ajaxQuery'), {sql: $('#sql').val(), filters: filters, recPerPage: DataStorage.recPerPage, pageID: DataStorage.pageID}, function(resp)
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
            getFieldSettings();
            addResult(resp);
            initPager(resp);
            if(typeof callback == 'function') callback();
        }
    });
}

/**
 * Add query result.
 *
 * @access public
 */
function addResult(resp)
{
    $('#queryResult').remove();

    var lineCount   = resp.lineCount;
    var columnCount = resp.columnCount;
    var queryMsg    = queryResult;

    queryMsg = queryMsg.replace('%s', lineCount);
    queryMsg = queryMsg.replace('%s', columnCount);

    $('#queryTable .table-footer').append("<span style='float:left;line-height:28px' id='queryResult'>" + queryMsg + "</span>");
}

function initPager(resp)
{
    var pageID     = parseInt(resp.pageID);
    var recPerPage = parseInt(resp.recPerPage);
    var recTotal   = parseInt(resp.lineCount);
    var pageTotal  = parseInt(Math.ceil(recTotal / recPerPage));

    $('.recTotal').html(recTotalTip.replace('%s', recTotal));
    $('.recPerPage').html(recPerPageTip.replace('%s', recPerPage));

    $('.dropup li').removeClass('active');
    $('.dropup li a').each(function()
    {
        if($(this).data('size') == resp.recPerPage)
        {
            $(this).parent().addClass('active');
            return;
        }
    });

    $('.page-number').html('<strong>' + pageID + '</strong>/<strong>' + pageTotal + '</strong>');

    $('.left-page').data('page', pageID - 1);
    $('.right-page').data('page', pageID + 1);
    $('.last-page').data('page', pageTotal);

    $('.first-page,.left-page,.last-page,.right-page').removeClass('disabled');
    if(pageID == 1) $('.first-page,.left-page').addClass('disabled');
    if(pageID == pageTotal) $('.last-page,.right-page').addClass('disabled');
    $('.table-footer').toggle(!!recTotal);
}

/**
 * Save field settings.
 *
 * @access public
 * @return bool
 */
function saveSettings()
{
    var fieldSettings = DataStorage.clone('fieldSettings');
    var relatedObject = DataStorage.relatedObject;
    var objectFields  = DataStorage.objectFields;
    for(let index in DataStorage.fields)
    {
        var relatedTable = $('#relatedTable' + index).val();
        var relatedField = $('#relatedField' + index).val();
        fieldSettings[index].object = relatedTable;
        fieldSettings[index].field  = relatedField;

        if(relatedTable != relatedObject[index])
        {
            fieldSettings[index].type = 'object';
            if(typeof(objectFields) != 'undefined' && typeof(objectFields[relatedTable]) != 'undefined' && typeof(objectFields[relatedTable][index]) != 'undefined') fieldSettings[index].type = objectFields[relatedTable][index].type == 'object' ? 'string' : objectFields[relatedTable][index].type;
        }
        if(relatedTable == relatedObject[index] && fieldSettings[index].type == 'object') fieldSettings[index].type = 'string';
    }

    DataStorage.fieldSettings = fieldSettings;

    var langs = DataStorage.clone('langs');
    $("input[name^='langs']").each(function()
    {
        var field = $(this).data('field');
        var lang  = $(this).data('lang');
        var value = $(this).val();

        if(typeof(langs[field]) == 'undefined') langs[field] = {};
        langs[field][lang] = value;
    })
    DataStorage.langs = langs;

    $('#addModal').modal('hide');
    this.drawTable(DataStorage.fields, DataStorage.rows);
    return false;
}

/**
 * Draw table content for sql result.
 *
 * @param object fields
 * @param array rows
 * @access public
 * @return void
 */
function drawTable(fields, rows)
{
    $.post(createLink('dataview', 'ajaxGetFieldName'), {fields: fields, fieldSettings: DataStorage.fieldSettings}, function(resp)
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
        var head = '<tr>';
        var langs = DataStorage.langs;
        for(let field in fields)
        {
            var fieldName = fields[field];
            if(!$.isEmptyObject(langs) && typeof(langs[field]) !== 'undefined') fieldName = langs[field][clientLang] ? langs[field][clientLang] : fieldName;
            head += '<th>' + fieldName + '</th>';
        }
        head += '</tr>';

        var html = '<thead>' + head + '</thead>';

        var body = '';
        for(let row of rows)
        {
            body += '<tr>';
            for(let index in row) body += '<td><div class="ellipsis-multi" title="' + row[index] + '">' + row[index] + '</div></td>';
            body += '</tr>';
        }

        html += '<tbody>' + body + '</tbody>';

        $('table.result').html(html);
    });
}

/**
 * Get text width.
 *
 * @param string text
 * @access public
 * @return float
 */
function getTextWidth(text)
{
    var font = "bold 12pt arial"
    var canvas = document.createElement("canvas");
    var context = canvas.getContext("2d");
    context.font = font;
    var measure = context.measureText(text);

    return measure.width;
}

/**
 * Get field settings.
 *
 * @access public
 * @return float
 */
function getFieldSettings()
{
    var fieldSettings = DataStorage.clone('fieldSettings');
    var fields        = DataStorage.fields;
    var columns       = DataStorage.columns;
    var relatedObject = DataStorage.relatedObject;
    var objectFields  = DataStorage.objectFields;

    var fieldSettingsNew = {};

    for(let index in fields)
    {
        var field         = fields[index];
        var defaultType   = columns[index];
        var defaultObject = relatedObject[index];

        if(typeof(objectFields) != 'undefined' && typeof(objectFields[defaultObject]) != 'undefined' && typeof(objectFields[defaultObject][index]) != 'undefined') defaultType = objectFields[defaultObject][index].type == 'object' ? 'string' : objectFields[defaultObject][index].type;

        if(!fieldSettings[index])
        {
            fieldSettingsNew[index] = {
                name: field,
                object: defaultObject,
                field: index,
                type: defaultType
            };
        }
        else
        {
            var object = fieldSettings[index].object;
            if(!object || object.length == 0) object = defaultObject;
            fieldSettings[index].object = object;

            var field = fieldSettings[index].field;
            if(!field || field.length == 0)
            {
                fieldSettings[index].field  = index;
                fieldSettings[index].object = defaultObject;
                fieldSettings[index].type   = 'string';
            }

            var type = fieldSettings[index].type;
            if(object == defaultObject && type != defaultType) fieldSettings[index].type = defaultType;

            fieldSettingsNew[index] = fieldSettings[index];
        }
    }

    DataStorage.fieldSettings = fieldSettingsNew;
}

function submitForm()
{
    if($('#fields').length == 0)  $('#dataform table').before($('<input>').attr({name: 'fields', id: 'fields'}).addClass('hidden'));
    if($('#langs').length == 0)   $('#dataform table').before($('<input>').attr({name: 'langs', id: 'langs'}).addClass('hidden'));

    getFieldSettings();

    /* Fix bug #26716. */
    var fieldSettings = DataStorage.clone('fieldSettings');
    var fields        = DataStorage.fields;
    for(let index in fieldSettings)
    {
      if(!Object.keys(fields).includes(index)) delete fieldSettings[index];
    }
    DataStorage.fieldSettings = fieldSettings;

    $('#fields').val(JSON.stringify(DataStorage.fieldSettings));
    $('#langs').val(JSON.stringify(DataStorage.langs));
    $('#dataform').submit();
}

/**
 * Set related field.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function setRelatedField(obj)
{
    var selectedTable = $(obj).val();
    var relatedFields = [];
    var objectFields  = DataStorage.objectFields;
    for(let index in objectFields[selectedTable]) relatedFields[index] = objectFields[selectedTable][index].name;
    var relatedFieldOptions = Object.keys(relatedFields).map(function(key)
    {
        return {text: relatedFields[key], value: key};
    });

    var $relatedField = $(obj).closest('tr').find("select[name^='relatedField']");
    var picker        = $relatedField.data('zui.picker');

    if(picker) picker.destroy();
    $relatedField.picker({list: relatedFieldOptions, autoselectfirst: true});
}

/**
 * Active field.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function activeField(obj)
{
    if(currentTheme == 'default')
    {
        $(obj).closest('tr').find('#' + $(obj).data('field')).css('color', '#2e7fff');
    }
    else
    {
        $(obj).closest('tr').find('#' + $(obj).data('field')).addClass('text-primary');
    }
}

/**
 * Remove field active.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function removeActive(obj)
{
    if(currentTheme == 'default')
    {
        $(obj).closest('tr').find('#' + $(obj).data('field')).css('color', '#0b0f18');
    }
    else
    {
        $(obj).closest('tr').find('#' + $(obj).data('field')).removeClass('text-primary');
    }
}

$(document).ready(function()
{
    $('#sql').on('input', function()
    {
        $('.error').addClass('hidden');
        // fieldSettings = {};
    })

    $('#sql').on('change', function()
    {
        DataStorage.fieldSettings = {};
        DataStorage.langs = {};
    })

    if($('#sql').val()) setTimeout(query, 500);
    $('.query').click(function()
    {
        DataStorage.pageID = 1;
        query();
    });

    $('.fieldSettings').click(function() {
        query(function() {

            var formTpl = $('#fieldSettingTpl').html();
            var formHtml = $(formTpl);

            var tbodyTpl = $('#tbodyTpl').html();

            var fieldTypeOptions = Object.keys(lang.objects).map(function(key)
            {
                return {text: lang.objects[key], value: key};
            });

            var fieldSettings = DataStorage.fieldSettings;
            var objectFields  = DataStorage.objectFields;
            var langs         = DataStorage.langs;
            var tbodyHtmls = Object.keys(fieldSettings).map(function(field)
            {
                var relatedFields       = [];
                var relatedObjectFields = objectFields[fieldSettings[field].object];
                for(let index in relatedObjectFields) relatedFields[index] = relatedObjectFields[index].name;
                var relatedFieldOptions = Object.keys(relatedFields).map(function(key)
                {
                    return {text: relatedFields[key], value: key};
                });

                var html = $($.zui.formatString(tbodyTpl, {field: field, name: fieldSettings[field].name}));
                html.find('select#relatedTable' + field).picker({list: fieldTypeOptions, defaultValue: fieldSettings[field].object});
                html.find('select#relatedField' + field).picker({list: relatedFieldOptions, defaultValue: fieldSettings[field].field});
                html.find("input[name^='langs']").each(function()
                {
                    var field = $(this).data('field');
                    var lang  = $(this).data('lang');

                    var fieldName = '';
                    if(lang == clientLang)
                    {
                        var relatedFieldName = $(this).closest('tr').find('select#relatedField' + field + '~ div.picker').find('span.picker-selection-text').text();
                        fieldName = relatedFieldName ? relatedFieldName : DataStorage.fields[field];
                    }
                    fieldName = !$.isEmptyObject(langs) && typeof(langs[field]) != 'undefined' && typeof(langs[field][lang]) != 'undefined' && langs[field][lang] ? langs[field][lang] : fieldName;
                    $(this).val(fieldName);
                });
                return html;
            });

            formHtml.find('tbody').append(tbodyHtmls);

            var modal = $('#addModal');
            modal.find('.modal-body').html(formHtml);
            modal.modal({width: 500});
        })
    });

    $('#save').click(function()
    {
        if(typeof dataview !== 'undefined' && dataview.used)
        {
            bootbox.confirm(warningDesign, function(res) { if(res) query(submitForm); });
        }
        else
        {
            query(submitForm);
        }
    });

    $('.first-page,.left-page,.last-page,.right-page').click(function()
    {
        DataStorage.pageID = $(this).data('page');
        query();
    });

    $('.dropup li a').click(function()
    {
        DataStorage.pageID     = 1;
        DataStorage.recPerPage = $(this).data('size');
        query();
    })
});
