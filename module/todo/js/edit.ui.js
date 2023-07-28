loadList(defaultType, '', defaultType, objectID);

if(cycleType) toggleCycleConfig(cycleType);

/**
 * 更改待办类型。
 * Change todo type.
 *
 * @param  object typeSelect
 * @return void
 */
window.changeType = function(obj)
{
    type = $(obj).zui('picker').$.value;
    loadList(type, '', defaultType, objectID);
}
