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
    const endDate    = isLongTime ? LONG_TIME : formatDate(beginDate, delta - 1);

    $('#end').toggleClass('hidden', isLongTime).zui('datePicker').$.setValue(endDate);
    $('#end').next().toggleClass('hidden', !isLongTime);
    $('#days').closest('.form-row').toggleClass('hidden', isLongTime);
}
