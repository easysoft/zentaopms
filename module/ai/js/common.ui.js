window.getTestingLocation = function(id)
{
    $.get($.createLink('ai', 'ajaxTestPrompt', `promptID=${id}`), function(response)
    {
        const res = JSON.parse(response);
        if (res.data) {
            parent.executeZentaoPrompt(res.data, true);
        } else if (res.error) {
            zui.Messager.show({content: res.error, type: 'danger'});
        }
    });
}
