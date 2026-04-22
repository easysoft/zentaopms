window.showSaveModal = function(editor, type)
{
    $('zen-editor[name="' + editor + '"]')[0].getHTML().then((content) => {
        content = content.replace(/<p><\/p>$/, '');
        if(!content)
        {
            zui.Modal.alert(templateEmpty);
            return false;
        }
        else
        {
            zui.Modal.open({url: $.createLink('user', 'ajaxSaveTemplate', 'editor=' + editor + '&type=' + type), size: 'sm'});
        }
    });
}

window.applyTemplate = function(editor, content)
{
    $('zen-editor[name="' + editor + '"]')[0].setHTML(content);
}

window.deleteTemplate = function(templateID)
{
    zui.Modal.confirm(confirmDeleteTemplate).then((result) => {
        if(result) $.ajaxSubmit({url: $.createLink('user', 'ajaxDeleteTemplate', 'id=' + templateID)});
    });
}

window.getZenEditorExtensions = function(editor)
{
    let mentionsSetting = $(editor.element).attr('mentions') || {};
    if(typeof mentionsSetting === 'string') mentionsSetting = zui.evalValue(mentionsSetting);
    if(Array.isArray(mentionsSetting)) mentionsSetting = {mentions: mentionsSetting};
    if(!mentionsSetting.mentions)
    {
        mentionsSetting.mentions = async (props) =>
        {
            const mentions = await zui.fetchData($.createLink('user', 'ajaxGetItems', `params=noclosed,nodeleted&search=${zui.encodeBase64(props.query)}&maxCount=10`));
            return mentions;
        };
    }
    return [zui.ZenEditor.Component.createMentionExtension('suggestions', {'@': mentionsSetting.mentions}, mentionsSetting.mentionOptions, mentionsSetting.suggestionsOptions)];
}
