$(document).ready(function()
{
    $("a.preview").colorbox({width:1000, height:550, iframe:true, transition:'none', scrolling:true});
    if(typeof(packageType) != 'undefined')
    {
        var hiddenDom = packageType == 'filePath' ? 'fileform' : 'filePath';
        $('#' + hiddenDom).parents('tr').addClass('hidden');
    }
    $("input[name='packageType']").bind('click', function()
    {
        if($(this).val() == 'path')
        {
            $('#filePath').parents('tr').removeClass('hidden');
            $('#fileform').parents('tr').addClass('hidden');
        }
        else
        {
            $('#filePath').parents('tr').addClass('hidden');
            $('#fileform').parents('tr').removeClass('hidden');
        }
    })
})
