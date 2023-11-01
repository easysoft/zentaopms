/**
 * 切换周期类型，用于展示周期类型的交互。
 * Toggle cycle, used to display the interaction of cycle.
 *
 * @param  element e
 * @return void
 */
function toggleCycle(e)
{
    $date = $('#date').zui('datePicker');
    if(e.target.checked)
    {
        $date.render({disabled: true});
        $('.date').prop('value', '');
        $('.cycle-date').prop('value', defaultDate);
        $('.cycle-config:not(.type-week,.type-month,.type-year)').removeClass('hidden');
        $('#switchDate').prop('checked', false);
        $('#switchDate').closest('.input-group-addon').addClass('hidden');
        $('#type').closest('.form-row').addClass('hidden');
        $('#type').zui('picker').$.setValue('custom');
        loadList('custom'); //Fix bug 3278.
    }
    else
    {
        $('.cycle-date').prop('value', '');
        $date.render({disabled: false});
        $('.cycle-config').addClass('hidden');
        $('#switchDate').closest('.input-group-addon').removeClass('hidden');
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
    changeDate(e);
    var selectTime = $(e.target).val() != today ? start : nowTime;
    zui.Picker.query('#begin').$.setValue(selectTime);
    selectNext();
}
