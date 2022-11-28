/* Update other picker on change */
$.zui.Picker.DEFAULTS.onChange = function(event)
{
    var picker = event.picker;
    if(!picker.$formItem.is('[name^=accounts]')) return;

    var select  = picker.$formItem[0];
    var newItem = event.value.length ? $.extend({}, picker.getListItem(event.value), {disabled: true}) : $.extend({}, picker.getListItem(event.oldValue), {disabled: false});

    $('.picker-select[name^=accounts]').each(function()
    {
        if(this === select) return;

        var $select      = $(this);
        var selectPicker = $select.data('zui.picker');

        if(selectPicker) selectPicker.updateOptionList([$.extend({}, newItem)]);
    });
}

$(function()
{
    $('#execution_chosen, #execution + .picker').click(function()
    {
        $('#execution_chosen ul li, #pickerDropMenu-pk_execution div a').each(function(index)
        {
            if(index == 0)
            {
                var projectName = subString($(this).text(), 56);
                $(this).text(projectName);
                $(this).append(' <label class="label">' + projectCommon + '</label>');
            }
            else
            {
                $(this).prepend('&nbsp;&nbsp;&nbsp;');
            }
        })
    });
})

/**
 * Set role when select an account.
 *
 * @param  string $account
 * @param  int    $roleID
 * @access public
 * @return void
 */
function setRole(account, roleID)
{
    role    = roles[account];       // get role according the account.
    roleOBJ = $('#role' + roleID);  // get role object.
    roleOBJ.val(role)               // set the role.
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
    var item = $('#addItem').html().replace(/%i%/g, itemIndex);
    $('<tr class="addedItem">' + item  + '</tr>').insertAfter($(obj).closest('tr'));
    var $accounts = $('#hours' + itemIndex).closest('tr').find('select:first')

    if($accounts.attr('data-pickertype') != 'remote')
    {
        $accounts.addClass('picker-select').picker({chosenMode: true});
    }
    else
    {
        $accounts.parent().find('.picker.picker-ready').remove();
        var pickerremote = $accounts.attr('data-pickerremote');
        $accounts.addClass('picker-select').picker({chosenMode: true, remote: pickerremote});
    }
    itemIndex ++;

    var disabledItems = [];
    $('.picker-select[name^=accounts]').each(function()
    {
        if(this === $accounts[0]) return;
        var $select = $(this);
        var picker = $select.data('zui.picker');
        if(!picker) return;
        var selectItem = picker.getListItem(picker.getValue());
        if(selectItem) disabledItems.push($.extend({}, selectItem, {disabled: true}));
    });
    if(disabledItems.length) $accounts.data('zui.picker').updateOptionList(disabledItems);
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
    if($('#teamForm .table tbody').children().length < 2) return false;

    $(obj).closest('tr').find('.picker .picker-selection-remove').click();
    $(obj).closest('tr').remove();
}

/**
 * Set dept users.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function setDeptUsers(obj)
{
    dept = $(obj).val(); // Get dept ID.
    link = createLink('execution', 'manageMembers', 'executionID=' + executionID + '&team2Import=' + team2Import + '&dept=' + dept); // Create manageMembers link.
    location.href=link;
}

/**
 * Chose team to copy.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function choseTeam2Copy(obj)
{
    team = $(obj).val();
    dept = $('#dept').val();
    link = createLink('execution', 'manageMembers', 'executionID=' + executionID + '&team2Import=' + team + '&dept=' + dept);
    location.href=link;
}

/**
 * Cut a string of letters and characters with the same length.
 *
 * @param  string $title
 * @param  int    $stringLength
 * @access public
 * @return string
 */
function subString(title, stringLength)
{
    if(title.replace(/[\u4e00-\u9fa5]/g, "**").length > stringLength)
    {
        var length = 0;
        for(var i = 0; i < title.length; i ++)
        {
            length += title.charCodeAt(i) > 255 ? 2 : 1;
            if(length > stringLength)
            {
                title = title.substring(0, i) + '...';
                break;
            }
        }
    }

    return title;
}
