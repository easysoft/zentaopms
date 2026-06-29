window.getTestingLocation = function(id)
{
    $.getJSON($.createLink('ai', 'ajaxTestPrompt', `promptID=${id}`), data =>
    {
        const info = (data && typeof data === 'object') ? data : null;
        if(info && info.data) return parent.executeZentaoPrompt(info.data, true);
        if(info && info.message) return zui.Messager.fail(info.message);
        zui.Messager.fail();
    }).catch(error => zui.Messager.fail(String(error)));
};

window.goPromptTesting = function(id)
{
    $.ajax(
    {
        url: $.createLink('ai', 'promptFinalize', 'promptID=' + id),
        type: 'POST',
        data: {goTesting: 1},
        dataType: 'json'
    })
    .done(function(response)
    {
        var info    = response && typeof response === 'object' ? response : null;
        var message = info ? (info.msg || info.message) : '';
        if(message) zui.Messager.show({content: message, type: info.result === 'success' ? 'success' : 'danger'});

        if(info && info.result === 'success' && info.locate) return openUrl(info.locate);
        if(!message) zui.Messager.fail();
    })
    .fail(function(xhr, status, error)
    {
        zui.Messager.fail(String(error));
    });
};
