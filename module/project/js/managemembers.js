/* Update other picker on change */
$.zui.Picker.DEFAULTS.onChange = function(event)
{
    var picker = event.picker;
    if(!picker.$formItem.is('[name^=accounts]')) return;

    var select  = picker.$formItem[0];
    var newItem = event.value.length ? $.extend({}, picker.getListItem(event.value), {disabled: true}) : $.extend({}, picker.getListItem(event.oldValue), {disabled: false});

    $('.user-picker[name^=accounts]').each(function()
    {
        if(this === select) return;

        var $select      = $(this);
        var selectPicker = $select.data('zui.picker');

        if(selectPicker) selectPicker.updateOptionList([$.extend({}, newItem)]);
    });
}


/**
 * Save team members.
 *
 * @access public
 * @return void
 */
function saveMembers()
{
    var isDeleted   = false;
    var accountList = [];
    $("[name^='accounts']").each(function()
    {
        if($(this).val()) accountList.push($(this).val());
    })

    oldAccountList.forEach(function(account)
    {
        if(accountList.indexOf(account) < 0)
        {
            isDeleted = true;
            return false;
        }
    })

    if(!isDeleted)
    {
        $('#saveBtn').addClass('hidden');
        $('#submit').removeClass('hidden');
        $('#submit').click();
    }
    else
    {
        bootbox.confirm(unlinkExecutionMembers, function(result)
        {
            $('#saveBtn').addClass('hidden');
            $('#submit').removeClass('hidden');
            if(result) $('#removeExecution').val('yes');
            $('#submit').click();
        });
    }
}

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
    $('#roles' + roleID).val(roles[account]);
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
    var $tr  = $('<tr class="addedItem">' + item  + '</tr>').insertAfter($(obj).closest('tr'));
    var $accounts = $tr.find('select:first').addClass('user-picker').trigger('list:updated').picker({type: 'user'});
    itemIndex++;

    var disabledItems = [];
    $('.user-picker[name^=accounts]').each(function()
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
 * Set selected Department.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function setDeptUsers(obj)
{
    dept = $(obj).val();
    link = createLink('project', 'manageMembers', 'projectID=' + projectID + '&dept=' + dept + '&copyProjectID=' + copyProjectID);
    location.href = link;
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
    copyProjectID = $(obj).val();
    locateLink    = createLink('project', 'manageMembers', 'projectID=' + projectID + '&dept=' + deptID + '&copyProjectID=' + copyProjectID);
    location.href = locateLink;
}
