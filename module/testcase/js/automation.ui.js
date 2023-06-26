function loadProduct()
{
    $('#shell').val('');
    $('#scriptPath').val('');

    var productID = $('#product').val();
    var url = createLink('zanode', 'ajaxGetZTFScript', "type=product&objectID=" + productID)
    $.get(url, function(result)
    {
        if(result.result == 'success')
        {
            data = result.data;
            if(!data) return false;

            $('#node').val(data.node)
            $('#shell').val(data.shell);
            $('#scriptPath').val(data.scriptPath);

            if($('[name=id]').length) $('[name=id]').val(data.id);
        }
    }, 'json');
}
