$(function()
{
    $("[name='URAndSR']").change(function()
    {
        if($(this).val() == 0)
        {
            $('#URSRName').addClass('hidden');
            $('#customURSR').addClass('hidden');
        }
        else
        {
            $('#URSRName').removeClass('hidden');
        }
    });

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
