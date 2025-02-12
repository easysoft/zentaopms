window.applyTemplate = function()
{
    const docApp = getDocApp();
    const editor = docApp.editor.$;

    const templateID = $('#template').find('.picker-box').zui('picker').$.value;
    const url = $.createLink('doc', 'ajaxGetTemplateContent', `id=${templateID}`);
    $.get(url, function(data)
    {
        data = JSON.parse(data);
        editor.updateDoc(data.content, data.type != 'doc' ? data.type : undefined);
    });
}
