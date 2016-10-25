$(function()
{
    toggleAcl($('#acl').val());
    window.editor['content'].addListener('focus', function()
    {
        this.ui.setFullScreen(true);
    });
    $('#type').change(function()
    {
        var type = $(this).val();
        if(type == 'html')
        {
            $('.contenthtml').removeClass('hidden');
            $('.contentmarkdown').addClass('hidden');
        }
        else if(type == 'markdown')
        {
            $('.contenthtml').addClass('hidden');
            $('.contentmarkdown').removeClass('hidden');
        }
    })
})
