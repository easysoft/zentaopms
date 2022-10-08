/* Update other picker on change */
$.zui.Picker.DEFAULTS.onChange = function(event)
{
    var picker = event.picker;
    if(!picker.$formItem.is('[name^=branches]')) return;

    var select  = picker.$formItem[0];
    var newItem = event.value.length ? $.extend({}, picker.getListItem(event.value), {disabled: true}) : $.extend({}, picker.getListItem(event.oldValue), {disabled: false});

    $('.user-picker[name^=branches]').each(function()
    {
        if(this === select) return;

        var $select      = $(this);
        var selectPicker = $select.data('zui.picker');

        if(selectPicker) selectPicker.updateOptionList([$.extend({}, newItem)]);
    });
}

/**
 * Save branch priv.
 *
 * @access public
 * @return void
 */
function savePriv()
{
    $('#saveBtn').addClass('hidden');
    $('#submit').removeClass('hidden');
    $('#submit').click();
}

/**
 * Add item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function addItem(obj)
{
    var item      = $('#addItem').html().replace(/%i%/g, itemIndex);
    var $tr       = $('<tr class="addedItem">' + item  + '</tr>').insertAfter($(obj).closest('tr'));
    var $branches = $tr.find('select').addClass('user-picker').trigger('list:updated').picker({type: 'user'});
    itemIndex++;

    var disabledItems = [];
    $('.user-picker[name^=branches]').each(function()
    {
        if(this === $branches[0]) return;
        var $select = $(this);
        var picker  = $select.data('zui.picker');
        if(!picker) return;
        var selectItem = picker.getListItem(picker.getValue());
        if(selectItem) disabledItems.push($.extend({}, selectItem, {disabled: true}));
    });
    if(disabledItems.length) $branches.data('zui.picker').updateOptionList(disabledItems);
}

/**
 * Delete item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function deleteItem(obj)
{
    if($('#privForm .table tbody').children().length < 2) return false;

    $(obj).closest('tr').find('.picker .picker-selection-remove').click();
    $(obj).closest('tr').remove();
}
