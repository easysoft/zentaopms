function sendCaptcha(event, type)
{
    const $target = $(event.target);
    const url     = $target.data('url');
    const data    = [];

    data[type] = $target.closest('.input-group').find('input').val();

    $.post(url, data, function(response)
    {
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
