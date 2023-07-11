selectNext();

/**
 * 切换周期类型，用于展示周期类型的交互。
 * Toggle cycle, used to display the interaction of cycle.
 *
 * @param  element e
 * @return void
 */
function toggleCycle(e)
{
    if(e.target.checked)
    {
        $('.date').attr('disabled', 'disabled');
        $('.date').prop('value', '');
        $('.cycle-date').prop('value', defaultDate);
        $('.cycle-config:not(.type-week,.type-month,.type-year)').removeClass('hidden');
        $('#switchDate').prop('checked', false);
        $('#type').closest('.form-row').addClass('hidden');
        $('#type').val('custom');
        loadList('custom'); //Fix bug 3278.
    }
    else
    {
        $('.cycle-date').prop('value', '');
        $('.date').removeAttr('disabled');
        $('.cycle-config').addClass('hidden');
        $('#type').closest('.form-row').removeClass('hidden');
    }
}

/**
 * 更改待办日期。
 * Change todo date.
 *
 * @return void
 */
function changeCreateDate(e)
{
    changeDate(e.target);
    var selectTime = $(e.target).val() != today ? start : nowTime;
    $('#begin').val(selectTime);
    selectNext();
}
