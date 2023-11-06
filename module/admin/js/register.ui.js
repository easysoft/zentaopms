function sendCaptcha(event, type)
{
    var $target = $(event.target);
    var url     = $target.data('url');
    var data    = {[type]: $target.closest('.input-group').find('input').val()};

    $.post(url, data, function(response)
    {
        response = JSON.parse(response);
        if(response.result == 'success')
        {
            zui.Messager.show({content: response.message, type: 'success', time: 1500});
        }
        else
        {
            zui.Modal.alert(response.message);
        }
    }, 'json')
    return false;
};
