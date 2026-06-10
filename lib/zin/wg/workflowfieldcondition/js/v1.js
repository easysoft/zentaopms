window.renderRowData = function($row, index, row)
{
    const name  = $($row).closest('.form-batch').attr('name');
    const table = $('input[name=table]').val();

    if(index == 0) $(".form-batch-row[data-index='0']").find("[data-name='" + name + "[logicalOperator]']").addClass('hidden');
    $row.find("[data-name='inputGroup']").find('.picker-box').on('inited', function(e, info)
    {
        if(index == 0) $(".form-batch-row[data-index='0']").find("[data-name='" + name + "[logicalOperator]']").addClass('hidden');
        if(name == 'wheres' && $(e.target).find('input').attr('name') == 'wheres[field]')
        {
            $.getJSON($.createLink('workflowhook', 'ajaxGetTableFields', 'table=' + table), function(response)
            {
                info[0].render({items: response});
            });
        }
    });

    if(name == 'fields')
    {
        $row.find("[data-name='fields[field]']").find('.picker-box').on('inited', function(e, info)
        {
            $.getJSON($.createLink('workflowhook', 'ajaxGetTableFields', 'table=' + table), function(response)
            {
                info[0].render({items: response});
            });
        });
    }

    $row.find("[data-name='" + name + "[param]']").find('.picker-box').on('inited', function(e, info)
    {
        processParamBox($(e.target).closest('.form-batch-row'), index + 1, row);
    });
}

window.changeFields = function(event)
{
    const $tr   = $(event.target).closest('.form-batch-row');
    const index = $tr.data('index') + 1;
    processParamBox($tr, index);
}

window.processParamBox = function($row, index, data)
{
    const name      = $row.closest('.form-batch').attr('name');
    const field     = $row.find("[name^='" + name + "[field]']").val();
    const paramType = $row.find("[name^='" + name + "[paramType]']").val();

    if(!paramType) return false;

    $row.find("[data-name='" + name + "[param]']").empty();
    switch(paramType)
    {
        case 'today':
        case 'now':
        case 'actor':
        case 'currentDept':
        case 'deptManager':
            $row.find("[data-name='" + name + "[param]']").append("<input class='form-control form-batch-input' disabled='disabled' type='text' autocomplete='off' value='" + datasources[paramType] + "'/><input class='hidden' type='text' name='" + name + "[param][" + index + "]' id='" + name + "[param]' data-name='" + name + "[param]' value='" + paramType + "'/>");
            break;
        case 'form':
        case 'record':
            $row.find("[data-name='" + name + "[param]']").append("<div id='" + name + "_param_" + index + "' class='form-group-wrapper picker-box'></div>");
            new zui.Picker(`#${name}_param_${index}`, {
                items: fieldItems,
                name: `${name}[param][${index}]`,
                defaultValue: data ? data[`${name}[param]`]  : ''
            });
            break;
        case 'formula':
            $row.find("[data-name='" + name + "[param]']").append("<input class='hidden' type='text' name='" + name + "[param][" + index + "]' id='" + name + "[param]' value='" + (data ? data[`${name}[param]`] : '') + "' data-name='" + name + "[param]'/><a data-on='click' data-call='setFormula' data-params='event' data-name='" + name + "[param][" + index + "]' class='btn ghost'>" + setFormula + "</a>");
            break;
        case 'custom':
            $.getJSON($.createLink('workflowfield', 'ajaxGetFieldOptions', 'module=' + moduleName + '&field=' + field), function(response)
            {
                processFieldControl($row, index, name, data, response, field);
            });
            break;
        default:
            $.getJSON($.createLink('workflowfield', 'ajaxGetParamOptions', 'paramType=' + paramType), function(response)
            {
                processFieldControl($row, index, name, data, {options: response}, field);
            });
            break;
    }
    setTimeout(function() {$row.find(`#${name}_param_${index}`).attr('data-name', name + '[param]');}, 1000);
}

window.processFieldControl = function($row, index, name, data, response, field)
{
    if(response.control == 'input')
    {
        $row.find("[data-name='" + name + "[param]']").append("<input class='form-control form-batch-input' type='text' autocomplete='off' name='" + name + "[param][" + index + "]' id='" + name + "[param]' data-name='" + name + "[param]' value='" + (data ? data[`${name}[param]`] : '') + "'/>");
    }
    else if(response.control == 'datePicker')
    {
        $row.find("[data-name='" + name + "[param]']").append("<div id='" + name + "_param_" + index + "' class='form-group-wrapper picker-box'></div>");
        new zui.DatePicker(`#${name}_param_${index}`, {
            name: `${name}[param][${index}]`,
            defaultValue: data ? data[`${name}[param]`]  : ''
        });
    }
    else if(response.control == 'datetimePicker')
    {
        $row.find("[data-name='" + name + "[param]']").append("<div id='" + name + "_param_" + index + "' class='form-group-wrapper picker-box'></div>");
        new zui.DatetimePicker(`#${name}_param_${index}`, {
            name: `${name}[param][${index}]`,
            defaultValue: data ? data[`${name}[param]`]  : ''
        });
    }
    else
    {
        if(currentModule == 'workflowhook' && field == 'assignedTo') response.options.splice(1, 0, {text: 'Closed', value: 'closed', key: 'closed'});
        $row.find("[data-name='" + name + "[param]']").append("<div id='" + name + "_param_" + index + "' class='form-group-wrapper picker-box'></div>");
        new zui.Picker(`#${name}_param_${index}`, {
            items: response.options,
            name: `${name}[param][${index}]`,
            defaultValue: data ? data[`${name}[param]`]  : '',
            multiple: name == 'fields' && response.multiple
        });
    }
    return true;
}

window.changeVarName = function(event)
{
    if($(event.target).val() != '') $('textarea[name=sql]').val($('textarea[name=sql]').val() + "'$" + $(event.target).val() + "'");
}
