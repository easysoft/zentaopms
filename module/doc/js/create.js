$(function()
{
    toggleAcl($('#acl').val());
    $('input[name="type"]').change(function()
    {
        var type = $(this).val();
        if(type == 'text')
        {
            $('#contentBox').removeClass('hidden');
            $('#urlBox').addClass('hidden');
        }
        else if(type == 'url')
        {
            $('#contentBox').addClass('hidden');
            $('#urlBox').removeClass('hidden');
        }
    })
    window.editor['content'].addListener('ready', function()
    {
        $('div#content .edui-toolbar').append("<div class='edui-box edui-button edui-for-markdown edui-default'><button type='button' class='edui-default' onclick='toggleEditor(\"markdown\")'>Markdown</button></div>");
    });
})

function toggleEditor(type)
{
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
    $('#contentType').val(type);
}
