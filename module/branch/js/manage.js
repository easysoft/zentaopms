function addItem()
{
    var $inputgroup = $('#branches .input-group:last');
    $('#branches').append($inputgroup.clone()).find('.input-group:last').find('input').val('');
}

function deleteItem(obj)
{
    if($(obj).closest('#branches').find("input[id^='newbranch']").size() <= 1) return;
    $(obj).closest('.input-group').remove();
}
