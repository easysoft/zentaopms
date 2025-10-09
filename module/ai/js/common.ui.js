window.getTestingLocation = function(id, module, targetForm)
{
    $.get($.createLink('ai', 'ajaxGetTestingLocation', `promptID=${id}&module=${module}&targetForm=${targetForm}`), function(response)
    {
        const res = JSON.parse(response);
        loadPage(res.data);
    });
}
