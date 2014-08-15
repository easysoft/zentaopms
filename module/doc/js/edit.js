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
        $('#module').parents('td').html(data);
        $("#module").removeAttr('onchange');
        $("#module").chosen(defaultChosenOptions);
    });
}

function changeByLib(lib)
{
    if(lib == 'product')
    {
        $('#product').parents('tr').show();
        $('#project').parents('tr').hide();
    }
    else if(lib == 'project')
    {
        $('#product').parents('tr').show();
        $('#project').parents('tr').show();
    }
    else
    {
        $('#product').parents('tr').hide();
        $('#project').parents('tr').hide();
    }
}

$(function()
{
    changeByLib($('#lib').val());
})
