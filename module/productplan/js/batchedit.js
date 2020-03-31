/**
 * Handle the product plan pending status. Fix bug #2937.
 *
 * @param  int planID
 * @access public
 * @return date
 */
function changeDate(planID)
{
    if($("#future"+planID).prop('checked'))
    {
        $("input[name='begin["+planID+"]']").val('2030-01-01').removeClass('form-input-show').addClass('form-input-hidden');
        $("input[name='end["+planID+"]']").val('2030-01-01').removeClass('form-input-show').addClass('form-input-hidden');
        $("input[name='begin"+planID+"']").val('').removeClass('form-input-hidden').addClass('form-input-show');
        $("input[name='end"+planID+"']").val('').removeClass('form-input-hidden').addClass('form-input-show');
    }
    else
    {
        $("input[name='begin["+planID+"]']").val('').removeClass('form-input-hidden').addClass('form-input-show');
        $("input[name='end["+planID+"]']").val('').removeClass('form-input-hidden').addClass('form-input-show');
        $("input[name='begin"+planID+"']").removeClass('form-input-show').addClass('form-input-hidden');
        $("input[name='end"+planID+"']").removeClass('form-input-show').addClass('form-input-hidden');
    }
};
