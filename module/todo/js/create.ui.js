selectNext();

/**
 * 切换周期类型，用于展示周期类型的交互。
 * Toggle cycle, used to display the interaction of cycle.
 *
 * @param  object tab
 * @return void
 */
function toggleCycle(switcher)
{
    if(switcher.checked)
    {
        $('.date').attr('disabled','disabled');
        $('.cycle-config').removeClass('hidden');
        $('#switchDate').closest('.checkbox-primary').addClass('hidden');
        $('#type').closest('.form-row').addClass('hidden');
        $('#type').val('custom');
        loadList('custom'); //Fix bug 3278.
    }
    else
    {
        $('.date').removeAttr('disabled');
        $('.cycle-config').addClass('hidden');
        $('#switchDate').closest('.checkbox-primary').removeClass('hidden');
        $('#type').closest('.form-row').removeClass('hidden');
    }
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
    var selectTime = $(dateInput).val() != today ? start : nowTime;
    $('#begin').val(selectTime);
    $('#begin').trigger("chosen:updated");
    selectNext();
}

/**
 * 切换标签页，用于更新标签页的样式和更新类型。
 * Toggle tabs to update the style and type.
 *
 * @param  object tab
 * @return void
 */
function toggleNavTabs(tab)
{
    $(tab).parent().siblings().find('a').prop('class', '');
    $(tab).addClass('active');
    if($(tab).data('type'))$('input[id*=type][id*=config]').val($(tab).data('type'));
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
