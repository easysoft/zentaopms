$(document).ready(function()
{ 
    $('#estimateTab').addClass('btn-active-text');
    $('input[name="hourPoint"]').change(function()
    {
        $('#saveTips').text('');

        /* Set the title and prompt information. */
        if($(this).val() != unit)
        {
            if($(this).val() == 0) var hourPoint = workingHours;
            if($(this).val() == 1) var hourPoint = storyPoint;
            if($(this).val() == 2) var hourPoint = functionPoint;

            var convertTitle = convertRelationTitle.replace('%s', hourPoint);
            var convertTips  = convertRelationTips.replace(/%s/g, hourPoint);
            var submitTips   = saveTips.replace(/%s/g, hourPoint);

            $('#title').text(convertTitle);
            $('#tips').text(convertTips);
            $('#convertFactor + span').text(hourPoint);
            $('#saveTips').text(submitTips);

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
    var scaleFactor = $('#convertFactor').val();

    /* Judgment of required items. */
    if(!scaleFactor)
    {
        alert(notempty);
    }
    else if(isNaN(scaleFactor))
    {
        alert(isNumber);
    }
    else
    {
        $('#scaleFactor').val(scaleFactor);
        $('#convertRelations').modal('hide');
    }
}
