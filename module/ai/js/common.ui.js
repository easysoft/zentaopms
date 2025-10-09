window.getTestingLocation = function(id, module, targetForm)
{
    $.get($.createLink('ai', 'ajaxGetTestingLocation', `promptID=${id}&module=${module}&targetForm=${targetForm}`), function(response)
    {
        const res = JSON.parse(response);
        if (res.data) {
            loadPage(res.data);
        } else if (res.error) {
            zui.Messager.show({content: res.error, type: 'danger'});
        }
    });
}
