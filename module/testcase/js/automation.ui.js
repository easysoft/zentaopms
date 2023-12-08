function loadProduct()
{
    $('#shell').val('');
    $('#scriptPath').val('');

    var productID = $('[name=product]').val();
    var url       = $.createLink('zanode', 'ajaxGetZTFScript', "type=product&objectID=" + productID)
    $.get(url, function(result)
    {
        if(result.result == 'success')
        {
            data = result.data;
            if(!data) return false;

            $('#node').picker('setValue', data.node);
            $('#shell').val(data.shell);
            $('#scriptPath').val(data.scriptPath);

            if($('[name=id]').length) $('[name=id]').val(data.id);
        }
    }, 'json');
}
