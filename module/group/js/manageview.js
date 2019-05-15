function toggleProduct()
{ 
    $('#productBox').toggle($('#product').prop("checked"));
}

function toggleProject()
{
    $('#projectBox').toggle($('#project').prop("checked"));
}

function selectAll(obj)
{
    $(obj).closest('tr').find(':checkbox').prop('checked', $(obj).prop('checked'));
}

$('input:checkbox[name^="allchecker"]').change(function()
{
    $('.group-item :checkbox[name^="actions"]').change();
});

$(function()
{
    $('.group-item :checkbox[name^="actions"]').change(function()
    {
        var allChecked = true;
        $('.group-item :checkbox[name^="actions"]').each(function()
        {
            if(!$(this).prop('checked')) allChecked = false;
        })
        $('.group-item input:checkbox[name^="allchecker"]').prop('checked', allChecked);

        var id = $(this).attr('id');
        if(id == 'product') toggleProduct();
        if(id == 'project') toggleProject();
        if($('#' + id + 'ActionBox').length == 1) $('#' + id + 'ActionBox').toggle($(this).prop("checked"));
    })
    $('.group-item :checkbox[name^="actions"]').change();
    $("tr[id$='ActionBox']").each(function()
    {
        var allChecked = true;
        $(this).find('td:last :checkbox').each(function()
        {
            if(!$(this).prop('checked'))
            {
                allChecked = false;
                return true;
            }
        });
        if(allChecked) $(this).find('th :checkbox').prop('checked', true);
    });
});
