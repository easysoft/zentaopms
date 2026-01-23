/**
 * Set role when select an account.
 *
 * @param  string $account
 * @param  int    $roleID
 * @access public
 * @return void
 */
window.setRole = function(e, roleID)
{
    const account = $(e.target).val();
    const role    = roles[account];
    const $role   = $('#role' + roleID);
    $role.val(role);

    resetAccountItems();
}

/**
 * Add item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.addItem = function(obj)
{
    let item         = $('#addItem > tbody').html().replace(/_i/g, itemIndex);
    const $currentTr = $(obj).closest('tr');
    $currentTr.after(item);

    const $newRow = $currentTr.next();
    $('#teamForm .table tbody tr .actions-list .btn-link').eq(1).removeClass('hidden');

    itemIndex ++;

    setTimeout(function()
    {
        let selectedAccounts = [];
        $('#teamForm [name^=account]').each(function()
        {
            if(!$(this).val()) return true;
            selectedAccounts.push($(this).val());
        });

        let $accountPicker = $newRow.find('input[name^=account]').zui('picker');
        if(typeof $accountPicker == 'undefined') return true;

        let userItems = $accountPicker.options.items;
        for(let key in userItems)
        {
            let disabled = selectedAccounts.includes(userItems[key].value) ? true : false;
            userItems[key].disabled = disabled;
        }
        $accountPicker.render({items: userItems});
    }, 100);
}

/**
 * Delete item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.deleteItem = function(obj)
{
    let currentAccount = $(obj).closest('tr').find('input[name^=account]').val();

    $(obj).closest('tr').remove();
    if($('#teamForm .table tbody tr').length < 2) $('#teamForm .table tbody tr .actions-list .btn-link').eq(1).addClass('hidden');

    if(!currentAccount) return true;
    resetAccountItems();
}

/**
 * Set dept users.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.setDeptUsers = function(e)
{
    const dept = $(e.target).val(); // Get dept ID.
    const link = $.createLink('project', 'manageMembers', 'projectID=' + projectID + '&dept=' + dept + '&copyProjectID=' + copyProjectID); // Create manageMembers link.
    isInModal ? loadModal(link) : loadPage(link);
}

/**
 * Chose team to copy.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function choseTeam2Copy(e)
{
    const copyProjectID = $(e.target).val();
    const dept          = $('input[name=dept]').val();
    const link          = $.createLink('project', 'manageMembers', 'projectID=' + projectID + '&dept=' + dept + '&copyProjectID=' + copyProjectID);
    isInModal ? loadModal(link) : loadPage(link);
}

window.changeProjectMembers = function()
{
    let isDeleted   = false;
    if(!noSprintProject)
    {
        let accountList = [];
        $("[name^='account']").each(function()
        {
            if($(this).val()) accountList.push($(this).val());
        });

        oldAccountList.forEach(function(account)
        {
            if(accountList.indexOf(account.toString()) < 0 && executionMembers.indexOf(account.toString()) !== -1)
            {
                isDeleted = true;
                return false;
            }
        });
    }

    if(!isDeleted)
    {
        const formData = new FormData($("#teamForm")[0]);
        const options  = isInModal ? {url: $('#teamForm').attr('action'), data: formData, callback: `renderTaskAssignedTo(${projectID})`, 'load': false, 'closeModal': true} : {url: $('#teamForm').attr('action'), data: formData};
        $.ajaxSubmit(options);
    }
    else
    {
        zui.Modal.confirm({message: unlinkExecutionMembers}).then((res) =>
        {
            if(res) $('#removeExecution').val('yes');
            const formData = new FormData($("#teamForm")[0]);
            const options  = isInModal ? {url: $('#teamForm').attr('action'), data: formData, callback: `renderTaskAssignedTo(${projectID})`, 'load': false, 'closeModal': true} : {url: $('#teamForm').attr('action'), data: formData};
            $.ajaxSubmit(options);
        });
    }
    return false;
}

function resetAccountItems()
{
    let selectedAccounts = [];
    $('#teamForm [name^=account]').each(function()
    {
        if(!$(this).val()) return true;
        selectedAccounts.push($(this).val());
    });

    $('#teamForm [name^=account]').each(function()
    {
        let $accountPicker = $(this).closest('input[name^=account]').zui('picker');
        if(typeof $accountPicker == 'undefined') return true;

        let userItems      = $accountPicker.options.items;
        let currentAccount = $(this).val();
        for(let key in userItems)
        {
            let disabled = selectedAccounts.includes(userItems[key].value) && userItems[key].value != currentAccount ? true : false;
            userItems[key].disabled = disabled;
        }
        $accountPicker.render({items: userItems});
    });
}
