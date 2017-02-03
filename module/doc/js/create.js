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
        $('div#content .edui-toolbar').append("<div class='edui-box edui-button edui-for-markdown edui-default'><div class='edui-default' onmouseover='toggleHover(this)' onmouseout='toggleHover(this)'><div class='edui-button-wrap edui-default' style='padding-right:4px;padding-left:4px;'><div class='edui-default' onclick='toggleEditor(\"markdown\")' title='Markdown'>Markdown</div></div></div></div>");
    });
})

function toggleHover(obj)
{
  if($(obj).hasClass('edui-state-hover')) return $(obj).removeClass('edui-state-hover');
  return $(obj).addClass('edui-state-hover');
}

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
