$(function()
{
    toggleAcl($('#acl').val());
    if(typeof(window.editor['content']) != 'undefined')
    {
        window.editor['content'].addListener('focus', function()
        {
            this.ui.setFullScreen(true);
        });
    }
})
