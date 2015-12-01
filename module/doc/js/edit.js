function loadModule(lib)
{
    if(lib == 'product')
    {
        type = 'productdoc';
        root = 0;
    }
    else if(lib == 'project')
    {
        type = 'projectdoc';
        root = 0;
    }
    else
    {
        type = 'customdoc';
        root = lib;
    }

    $.get(createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + root + '&viewType=' + type), function(data)
    {
        $('#module').closest('td').html(data);
        $("#module").removeAttr('onchange');
        $("#module").chosen(defaultChosenOptions);
    });
}

function changeByLib(lib)
{
    if(lib == 'product')
    {
        $('#product').closest('tr').show();
        $('#project').closest('tr').hide();
    }
    else if(lib == 'project')
    {
        $('#product').closest('tr').show();
        $('#project').closest('tr').show();
    }
    else
    {
        $('#product').closest('tr').hide();
        $('#project').closest('tr').hide();
    }
}

$(function()
{
    changeByLib($('#lib').val());
})
