function showSaveModal(editor, type)
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
