const timeIndex = times.findIndex(key => `${key}` === `${time}`);
window.renderRowData = function($row, index, row)
{
    if($row.find('td[data-name="beginAndEnd"] .inited').length == 0)
    {
        $tdDom = $row.find('td[data-name="beginAndEnd"]');
        $tdDom.empty().html($('#dateCellData').html());

        const beginIndex = timeIndex + index * 3;
        $tdDom.find('select[name="begin"]').attr('name', `begin[${index + 1}]`).val(times[beginIndex]);
        $tdDom.find('select[name="end"]').attr('name', `end[${index + 1}]`).val(times[beginIndex + 3]);

        $tdDom.find('input[name="switchTime"]').attr('name', `switchTime[${index + 1}]`).attr('id', `switchTime_${index}`);
        $tdDom.find('label[for="switchTime_"]').attr('for', `switchTime_${index}`);
    }

    if($row.find('td[data-name="name"].inited').length == 0)
    {
        $row.find('td[data-name="name"]').addClass(`name-box${index} inited`).attr('id', `nameBox${index}`);
    }
}

window.changeType = function(e)
{
    const type         = $(e.target).val();
    const index        = $(e.target).closest('tr').data('index')
    const nameBoxID    = '#nameBox'  + index;
    const nameBoxClass = '.name-box' + index;

    let param = 'userID=' + userID + '&id=' + (+index + 1);
    if(type == 'task') param += '&status=wait,doing';

    if(moduleList.indexOf(type) !== -1)
    {
        link = $.createLink(type, objectsMethod[type], param);
        $.get(link, function(data)
        {
            $(nameBoxClass).html(data);
        })
    }
    else
    {
        $(nameBoxClass).html($('#nameInputBox').html());
        $(nameBoxClass).find('input[name=name]').attr('name', `name[${index}]`).attr('id', `name_${index}`)
    }
}

window.changFuture = function()
{
    const isChecked = $('#futureDate').is(':checked');
    $('#batchCreateTodoForm td[data-name="date"] .form-batch-input').val($('#todoDate').val());
    if(isChecked) $('#batchCreateTodoForm td[data-name="date"] .form-batch-input').val(futureDate);

    $('#futureDate').closest('.input-group').find('input[name=date]').prop('disabled', isChecked);
}
