selectNext();

/**
 * 切换周期类型，用于展示周期类型的交互。
 * Toggle cycle, used to display the interaction of cycle.
 *
 * @param  object switcher
 * @return void
 */
function toggleCycle(switcher)
{
    if(switcher.checked)
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
 * @param  object dateInput
 * @return void
 */
function changeCreateDate(dateInput)
{
    changeDate(dateInput);
    var selectTime = $(dateInput).val() != today ? start : nowTime;
    $('#begin').val(selectTime);
    $('#begin').trigger('chosen:updated');
    selectNext();
}

/**
 * 更改待办类型。
 * Change todo type.
 *
 * @param  object typeSelect
 * @return void
 */
function changeType(typeSelect)
{
    loadList($(typeSelect).find('select').val(), '');
}
