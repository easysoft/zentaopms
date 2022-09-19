$(function()
{
    $('#modeTab').addClass('btn-active-text');
    $('[name=mode]').change(function()
    {
        if($(this).val() == 'lean')
        {
            $("#selectDefaultProgram").removeClass('hidden');
        }
        else
        {
            $("#selectDefaultProgram").addClass('hidden');
        }
    });
})
