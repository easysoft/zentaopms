/**
 * 修改选项配置。
 * Change option configuration.
 *
 * @param  target
 * @access public
 * @return void
 */
window.changeSetOption = function(target)
{
    $('.think-options-field').toggleClass('hidden', target.value == 1);
    $('.think-quote').toggleClass('hidden', target.value == 0);
    if(target.value == 1)
    {
        $('.min-count input').val(1).attr('disabled', 'disabled');
        $('.max-count input').val('').attr('placeholder', maxCountPlaceholder).attr('disabled', 'disabled');
    }
    else
    {
        $('.min-count input').val('').removeAttr('disabled');
        $('.max-count input').attr('placeholder', inputContent).removeAttr('disabled');
    }
    $('.text-danger').remove();
    $('.has-error').removeClass('has-error');
}