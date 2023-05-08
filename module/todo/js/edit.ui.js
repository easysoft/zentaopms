loadList($('#type').val(), '');

if(cycleType) toggleCycleConfig(cycleType);

/**
 * 更改待办类型。
 * Change todo type.
 *
 * @param  object typeSelect
 * @return void
 */
function changeType(typeSelect)
{
    loadList($(typeSelect).find('select').val(), '', idvalue);
}

/**
 * 更改待办日期。
 * Change todo date.
 *
 * @param  object tab
 * @return void
 */
function changeCreateDate(dateInput)
{
    $('#switchDate').prop('checked', !$(dateInput).val());
}
