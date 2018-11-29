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
    if($(obj).prop('checked'))
    {
        $(obj).closest('td').find(':checkbox').attr('checked', 'checked');
    }
    else
    {
        $(obj).closest('td').find(':checkbox').removeAttr('checked');
    }
}

$('input:checkbox[name^="allchecker"]').change(function()
{
    setTimeout(function(){toggleProduct(),toggleProject()}, 50);
});
$('#product').change(function(){toggleProduct();})
$('#project').change(function(){toggleProject();})

$(function()
{
    toggleProduct();
    toggleProject();
    $('.group-item :checkbox[name^="actions"]').change(function()
    {
        var allChecked = true;
        $('.group-item :checkbox[name^="actions"]').each(function()
        {
            if(!$(this).prop('checked')) allChecked = false;
        })
        $('input:checkbox[name^="allchecker"]').prop('checked', allChecked);
    })
})
