$(function()
{
    $('[name^="URSRCustom"]').change(function()
    {
        if($(this).attr('checked'))
        {
            $('#customURSR').removeClass('hidden');
        }
        else
        {
            $('#customURSR').addClass('hidden');
        }
    })
})

