window.getTestingLocation = function(id)
{
    return $.getJSON($.createLink('ai', 'ajaxTestPrompt', `promptID=${id}`), data =>
    {
        const info = (data && typeof data === 'object') ? data : null;
        if(info && info.data) return parent.executeZentaoPrompt(info.data, true);
        if(info && info.message) return zui.Messager.fail(info.message);
        zui.Messager.fail();
    }).catch(error => zui.Messager.fail(String(error)));
};

window.goPromptTesting = function(id)
{
    return getTestingLocation(id);
};
