var filterValues = {};

/**
 * Listen filter change.
 *
 * @param string field
 * @param string dateSelect
 * @param string $filterValue
 * @access public
 * @return void
 */
function filterChange(field, dateSelect, $filterValue)
{
    var parse = field.split('.');

    field = parse[0];
    var type = '';
    if(parse.length == 2) type = parse[1];
    var id = type.length == 0 ? field : field + '\\[' + type + '\\]';
    var value = $('#' + id).val();

    if(type.length == 0)
    {
        filterValues[field] = value;
        ajaxGetChart();
    }
    else
    {
        var check = checkDate(dateSelect, $filterValue);
        if(!check) return;
        if(!filterValues[field]) filterValues[field] = {begin: '', end: ''};

        filterValues[field][type] = value;

        var begin = filterValues[field].begin;
        var end   = filterValues[field].end;
        if((begin.length > 0 && end.length > 0) || (begin.length == 0 && end.length == 0)) ajaxGetChart();
    }
}
/**
 * Ajax get pivot data.
 *
 * @access public
 * @return bool
 */
function ajaxGetChart()
{
    /* If the same value is detected for the x and y axes, throw error and clear the echart, then return.*/
    /* 如果x轴和y轴重复了，那么抛出错误并返回。*/
    if(!checkFormSetting()) return;

    var chartParams = JSON.parse(JSON.stringify(DataStorage.pivot));
    chartParams.filterValues = filterValues;

    /* Redraw echart. */
    /* 拿数据并重绘图表。*/
    $.post(createLink('pivot', 'ajaxGetChart'), chartParams, function(resp)
    {
        var data = JSON.parse(resp);
        echart.clear();
        echart.setOption(data, true);
    });
}

function getQueryFilters(pivot)
{
    var filters = '';
    filters = pivot.filters;

    filters.forEach(function(filter, index)
    {
        if(filter.from == 'query')
        {
            var find = $('#queryFilters').find('.filter-item-' + index + ' .form-' + filter.type);
            if(find.length != 0) filter.default = find.val();
        }
    });

    return filters;
}

/**
 * If the same value is detected for the x and y axias, throw error and clear the echart, then return.
 *
 * @access public
 * @return bool    ture|false
 */
function checkFormSetting()
{
    var pivot = DataStorage.pivot;
    var type = pivot.settings[0].type;
    var result   = 'success';
    var errorMsg = '';
    if(checkForm[type])
    {
        for(var key in checkForm[type])
        {
            var fields = checkForm[type][key].split(',');
            var former = fields[0];
            var latter = fields[1];

            if(key == 'cantequal')
            {
                if(pivot.settings[0][latter].includes(pivot.settings[0][former]))
                {
                    result = 'fail';
                    errorMsg = pivotLang['errorList']['cantequal'];
                    errorMsg = errorMsg.replace(/%s/, pivotLang[type][latter]);
                    errorMsg = errorMsg.replace(/%s/, pivotLang[type][former]);
                }
            }
        }
    }

    if(result == 'fail')
    {
        var message = new $.zui.Messager(errorMsg,
        {
            html: true,
            icon: 'exclamation-sign',
            type: 'danger',
            close: true,
        });

        message.show();

        echart.clear();
        return false;
    }

    return true;
}

/**
 * Validate form required.
 *
 * @access public
 * @return void
 */
function validate(showError = false)
{
    var pivot        = DataStorage.pivot;
    var formSettings = pivot.settings;
    var isReady      = true;

    if("summary" in formSettings && formSettings.summary == 'notuse')
    {
        if(isReady) $('#datagrid-tip').addClass('hidden');
        return true;
    }

    /* check group settings. */
    var exist = false;
    for(var key in formSettings)
    {
        if(key.indexOf('group') != '-1' && formSettings[key].length > 0) exist = true;
    }

    var tr = $('#step2Content form#groupForm table tbody tr:nth-child(1)');
    tr.find('#groupLabel').remove();
    tr.find('#group1').removeClass('has-error');

    if(!exist)
    {
        isReady = false;

        if(showError)
        {
            var error = '<div id="groupLabel" class="text-danger help-text">' + moreThanOneLang + '</div>';
            tr.find('#group1').addClass('has-error');
            tr.find('#group1').next().after(error);
        }
    }

    /* check columns settings. */
    formSettings.columns.forEach(function(value, index)
    {
        var $column = $('#step2Content form#columnForm .column-' + index);
        $column.find('#column' + index + 'Label').remove();
        $column.find('#stat' + index + 'Label').remove();
        $column.find('#column').removeClass('has-error');
        $column.find('#stat').removeClass('has-error');

        if(!value.field || value.field.length == 0)
        {
            isReady = false;
            if(showError)
            {
                var error = '<div id="column' + index + 'Label" class="text-danger help-text">' + notemptyLang.replace('%s', pivotLang.step2.columnField) + '</div>';
                $column.find('#column').addClass('has-error');
                $column.find('#column').next().after(error);
            }
        }

        if(!value.stat || value.stat.length == 0 || value.stat === '')
        {
            isReady = false;
            if(showError)
            {
                var error = '<div id="stat' + index + 'Label" class="text-danger help-text">' + notemptyLang.replace('%s', pivotLang.step2.calcMode) + '</div>';
                $column.find('#stat').addClass('has-error');
                $column.find('#stat').next().after(error);
            }
        }
    });

    if(isReady)  $('#datagrid-tip').addClass('hidden');
    return isReady;
}

/**
 * Multi Validate.
 *
 * @param  setting $setting
 * @param  showError $showError
 * @access public
 * @return void
 */
function multiValidate(setting, showError)
{
    var pivot = DataStorage.pivot;
    var type = pivot.settings[0].type;
    var isReady = true;
    var field = setting.field;
    var error = '<div id="' + field + 'Label"' + ' class="text-danger help-text">' + notemptyLang.replace('%s', pivotLang[type][field]) + '</div>';
    $('#pivotForm .table-form').find('.multi-' + field).each(function()
    {
        $(this).parent('td').find('#' + field + 'Label').remove();
        $(this).parent('td').find('#' + field + '_chosen a').removeClass('has-error');

        if(setting.required && $(this).val().length == 0)
        {
            isReady = false;
            if(showError)
            {
                $(this).parent('td').find('#' + field + '_chosen a').addClass('has-error');
                $(this).parent('td').find('#' + field + '_chosen').after(error);
            }
        }
    });

    return isReady;
}

/**
 * Check date.
 *
 * @param  string dateSelect
 * @param  object $filterValue
 * @access public
 * @return void
 */
function checkDate(dateSelect, $filterValue)
{
    var begin = new Date($(dateSelect).parent().find('.default-begin').val().replace(/-/g, "\/")).getTime();
    var end   = new Date($(dateSelect).parent().find('.default-end').val().replace(/-/g, "\/")).getTime();
    if(begin > end)
    {
        $(dateSelect).val('');
        if(typeof $filterValue == 'object') $filterValue.val('');
        bootbox.alert(pivotLang.beginGtEnd);
        return false;
    }
    return true;
}

function waitForRepaint(callback)
{
    window.requestAnimationFrame(function()
    {
        window.requestAnimationFrame(callback);
    });
}

/**
 * Init picker.
 *
 * @access public
 * @return void
 */
function initPicker($row, pickerName = 'picker-select', onready = false)
{
    $row.find('.' + pickerName).picker(
    {
        maxDropHeight: pickerHeight,
        onReady: function()
        {
            if(!onready) return;
            if(!$row.find('.picker')) return;
            if(window.getComputedStyle($row.find('.picker').find('.picker-selections')[0]).getPropertyValue('width') !== 'auto')
            {
                var pickerWidth = $row.find('.picker')[0].getBoundingClientRect().width;
                $row.find('.picker').find('.picker-selections').css('width', pickerWidth);
            }
        }
    });
    $row.find("." + pickerName).each(function(index)
    {
       if($(this).hasClass('required')) $(this).siblings("div .picker").addClass('required');
    });
}

/**
 * Init datapicker.
 *
 * @param  object   $obj
 * @param  function callback
 * @access public
 * @return void
 */
function initDatepicker($obj, callback)
{
    $obj.find('.form-date').datepicker();
    $obj.find('.form-datetime').datetimepicker();
    if(typeof callback == 'function') callback($obj);
}

/**
 * Attr date check.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function attrDateCheck($obj)
{
    $obj.find('.form-date').attr('onchange', 'checkDate(this, this.value)');
    $obj.find('.form-datetime').attr('onchange', 'checkDate(this, this.value)');
}

/**
 * Export file.
 *
 * @param  object $domObj
 * @access public
 * @return void
 */
function exportFile($domObj)
{
    if(typeof $domObj == 'undefined') return;

    var fileName  = $('#fileName').val().trim() ? $('#fileName').val().trim() : untitled;
    var fileType  = $('#fileType').val();
    var tableName = fileName + '.' + fileType;

    if(fileType == 'xlsx' || fileType == 'xls')
    {
        const new_sheet = XLSX.utils.table_to_book($domObj, {raw: true});
        XLSX.writeFile(new_sheet, tableName);
    }
    else if(fileType == 'html' || fileType == 'mht')
    {
        const htmlContent = $domObj.outerHTML;

        const $temp = $('<div>').html(htmlContent);
        $temp.find('*').removeAttr('style');
        $temp.find('*').removeAttr('class');
        $temp.find('*').removeAttr('data-flex');
        $temp.find('*').removeAttr('data-width');
        $temp.find('*').removeAttr('data-type');
        $temp.find('*').removeAttr('data-fixed-left-width');
        const cleanTableHTML = $temp.html();

        var head  = '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        var style = '<style>table, th, td {font-size: 12px; border: 1px solid gray; border-collapse: collapse;}table th, table td {padding: 5px;}</style>';
        var title = '<title>' + fileName + '</title></head>';
        var body  = '<body>' + cleanTableHTML + '</body>';
        const finalHTML = head + style + title + body;

        if(fileType == 'html')
        {
            const blob = new Blob([finalHTML], { type: 'text/html;charset=utf-8' });
            saveAs(blob, tableName);
        }
        else if(fileType == 'mht')
        {
            const data = {html: finalHTML, fileName: fileName};
            $.post(createLink('file', 'ajaxExport2mht'), data, function(resp)
            {
                const blob = new Blob([resp], { type: "application/x-mimearchive" });
                saveAs(blob, tableName);
            });
        }
    }
    $('#export').modal('hide');
}

/**
 * Zui messager alert.
 *
 * @param  string result  success|fail
 * @param  string mes
 * @access public
 * @return void
 */
function zuiMessage(result, mes)
{
    var icon = result == 'success' ? 'check-circle' : 'exclamation-sign';
    var type = result == 'success' ? 'success' : 'danger';

    var message = new $.zui.Messager(mes,
    {
        html: true,
        icon: icon,
        type: type,
        close: true,
    });

    message.show();
}

function select(name, options, selected, attrib, callback)
{
    var type = 'option';
    if(!Array.isArray(options))
    {
        type = options;
        options = [];
    }
    $.post(createLink('pivot', 'ajaxGetSelect'),
    {
        name: name,
        type: type,
        selectedItems: selected,
        attrib: attrib,
        options: options
    }, function(resp)
    {
        if(typeof callback == 'function') callback(resp);
    });
}

function setDateField(query)
{
    var $period = $('#selectPeriod');
    if(!$period.length)
    {
        $period = $("<ul id='selectPeriod' class='dropdown-menu'><li><a href='#MONDAY'>" + datepickerText.TEXT_WEEK_MONDAY + "</a></li><li><a href='#SUNDAY'>" + datepickerText.TEXT_WEEK_SUNDAY + "</a></li><li><a href='#MONTHBEGIN'>" + datepickerText.TEXT_MONTH_BEGIN + "</a></li><li><a href='#MONTHEND'>" + datepickerText.TEXT_MONTH_END + "</a></li></ul>").appendTo('body');
        $period.find('li > a').click(function(event)
        {
            var target = $(query).parents('table, #queryFilters, #filterItems,#filterForm').find('[data-index="' + $period.attr('data-index') + '"]').find('#default:visible');
            if(target.length)
            {
                target.val($(this).attr('href').replace('#', '$'));
                $period.hide();
            }
            event.stopPropagation();
            return false;
        });
    }

    if(query == '.form-date')     $(query).datepicker();
    if(query == '.form-datetime') $(query).datetimepicker();

    $(query).on('show', function(e)
    {
        var $e   = $(e.target);
        var ePos = $e.offset();
        $period.css({'left': ePos.left + 210, 'top': ePos.top + 29, 'min-height': $('.datetimepicker').outerHeight(), 'z-index': 1110}).show().attr('data-index', $e.parents('.filter-item, .filter').data('index')).find('li.active').removeClass('active');
        $period.find("li > a[href='" + $e.val().replace('$', '#') + "']").closest('li').addClass('active');
    }).on('changeDate', function()
    {
        $period.hide();
    }).on('hide', function(){setTimeout(function(){$period.hide();}, 200);});
}
