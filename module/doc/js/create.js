$(function()
{
    toggleAcl($('#acl').val());
    window.editor['content'].addListener('focus', function()
    {
        this.ui.setFullScreen(true);
    });
})
