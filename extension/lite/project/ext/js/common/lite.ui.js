window.dateRangeChange = function()
{
    const beginDate = new Date($('[name=begin]').zui('datePicker').$.value);
    const endDate   = new Date($('[name=end]').zui('datePicker').$.value);
    const days      = parseInt((endDate.getTime() - beginDate.getTime()) / (24 * 60 * 60 * 1000)) + 1;
    if(parseInt($('input[name=delta]:checked').val()) != 999) $('[name=delta]').prop('checked', false);
    if($('#delta' + days).length > 0 && days != 999) $('#delta' + days).prop('checked', true);

    const begin = $('input[name=begin]').val();
    const end   = $('input[name=end]').val();
    $('[name=days]').val(computeDaysDelta(begin, end));
}

/**
 * 计算并设置计划完成时间。
 * Compute the end date for project.
 *
 * @access public
 * @return void
 */
function computeEndDate()
{
    const beginDate = $('#begin').zui('datePicker').$.value;
    if(!beginDate) return;

    const delta = parseInt($('input[name=delta]:checked').val());
    if(isNaN(delta)) return;

    const isLongTime = delta == 999;
    const endDate    = isLongTime ? '' : formatDate(beginDate, delta - 1);
    const $endPicker = $('[name=end]').zui('datePicker');
    $endPicker.render({disabled: isLongTime, defaultValue: endDate});
    $endPicker.$.setValue(endDate);

    if(isLongTime) $('input[name=days]').val('').attr('disabled', true);
    if(!isLongTime) $('input[name=days]').removeAttr('disabled');
}
