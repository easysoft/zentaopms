$(document).ready(function()
{ 
    $('#estimateTab').addClass('btn-active-text');
    $('input[name="hourPoint"]').change(function()
    {
        /* Show or hide conversion relation fields. */
        if($(this).val() == unit)
        {
            $('#convertRelations').addClass('hidden');
        }
        else
        {
            $('#convertRelations').removeClass('hidden');
        }

        if($(this).val() == 0)
        {
            $('#scaleFactor + span').text(workingHours);
            $('#efficiency + span').text(workingHours);
            $('#efficiency').val("1");
            $('.efficiency').addClass('hidden');
        }

        if($(this).val() == 1 || $(this).val() == 2)
        {
            $('#efficiency').val('');
            $('.efficiency').removeClass('hidden');
            if($(this).val() == 1)
            {
                $('#scaleFactor + span').text(storyPoint);
                $('#efficiency + span').text(efficiency + storyPoint);
            }

            if($(this).val() == 2)
            {
                $('#scaleFactor + span').text(functionPoint);
                $('#efficiency + span').text(efficiency + functionPoint);
            }
        }
    });
});
