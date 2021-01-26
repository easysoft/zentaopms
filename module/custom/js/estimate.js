$(document).ready(function()
{ 
    $('#estimateTab').addClass('btn-active-text');
    $('input[name="hourPoint"]').change(function()
    {
        /* Show or hide conversion relation fields. */
        if($(this).val() != unit)
        {
            if($(this).val() == 0) var hourPoint = workingHours;
            if($(this).val() == 1) var hourPoint = storyPoint;
            if($(this).val() == 2) var hourPoint = functionPoint;

            convertRelationTitle = convertRelationTitle.replace('%s', hourPoint);
            convertRelationTips  = convertRelationTips.replace(/%s/g, hourPoint);
            $('#factor + span').text(hourPoint);

            $('#title').text(convertRelationTitle);
            $('#tips').text(convertRelationTips);
            $('#convertRelations').modal({show: true});
        }

        if($(this).val() == 0)
        {
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
                $('#efficiency + span').text(efficiency + storyPoint);
            }

            if($(this).val() == 2)
            {
                $('#efficiency + span').text(efficiency + functionPoint);
            }
        }
    });
});

/**
 * Set scale factor.
 *
 * @access public
 * @return void
 */
function setScaleFactor()
{
    var scaleFactor = $('#factor').val();
    if(!scaleFactor)
    {
        alert(notempty);
    }
    else if(isNaN(scaleFactor))
    {
        alert(notNumber);
    }
    else
    {
        $('#scaleFactor').val(scaleFactor);
        $('#convertRelations').modal('hide');
    }
}
