$(function()
{
    $("[name='URAndSR']").change(function()
    {
        if($(this).val() == 0)
        {
            $('#URSRName').addClass('hidden');
        }
        else
        {
            $('#URSRName').removeClass('hidden');
        }
    });
})

