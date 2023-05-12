loadList($('#type').val(), '', defaultType, objectID);

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
    loadList($(typeSelect).find('select').val(), '', defaultType, objectID);
}
