function addItem(obj)
{
    var $inputgroup = $(obj).closest('.input-group').clone();
    $inputgroup.find('input').val('');
    $(obj).closest('.input-group').after($inputgroup);
}

function deleteItem(obj)
{
    if($(obj).closest('#newbranches').find("input[id^='newbranch']").size() <= 1) return;
    $(obj).closest('.input-group').remove();
}

$(function()
{
    $('#branches').sortable(
    {
        selector: '.input-group',
        dragCssClass: 'drag-row',
        trigger: $('#branches').find('.sort-handler').length ? '.sort-handler' : null,
        finish: function(e)
        {
            var list = '';
            $('#branches').find('.input-group').each(function(){list += $(this).attr('data-id') + ',';});
            $.post(createLink('branch', 'sort'), {'branches' : list});
        }
    });
});
