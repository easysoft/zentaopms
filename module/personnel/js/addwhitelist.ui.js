window.renderRowData = function($row, index, row)
{
    $row.find('[data-name="account"]').find('.picker-box').on('inited', function(e, info)
    {
        /* Get selected users. */
        let users = [];
        $('.add-whitelist-panel form [name^=account]').each(function()
        {
            value = $(this).val();
            if(value != '') users.push(value);
        });

        let $account = info[0];
        let isAppend = row != undefined && row.isAppend != undefined && row.isAppend;

        /* Remove selected account in picker items. */
        items = userItems;
        if(isAppend || row == undefined)
        {
            items = [];
            $account.options.items.forEach(function(userItem)
            {
                if(!users.includes(userItem.value)) items.push(userItem);
                if(row != undefined && row.account == userItem.value) items.push(userItem);
            })
        }

        $account.render({items: items, required: row != undefined, readonly: !isAppend && row != undefined});
        if(row != undefined) $account.$.setValue(row.account);
    });
}

/**
 * Users by dept,execution,project,product or program.
 *
 * @param  object $object
 * @access public
 * @return void
 */
function setObjectUsers()
{
    var copyID = $('input[name=object]').val();
    var deptID = $('input[name=dept]').val();
    var link   = $.createLink(module, moduleMethod, 'objectID=' + objectID + '&deptID=' + deptID + '&copyID=' + copyID + '&objectType=' + objectType + '&module=' + module);
    if(module == 'program') link = $.createLink(module, moduleMethod, 'objectID=' + objectID + '&deptID=' + deptID + '&copyID=' + copyID + '&programID=' + programID + '&from=' + from);

    loadPage(link, '.panel-body');
}

function changeUsers()
{
    let users     = [];
    let $accounts = $('.add-whitelist-panel form [name^=account]');
    $accounts.each(function()
    {
        value = $(this).val();
        if(value != '') users.push(value);
    });

    if(users.length == 0) return;

    $accounts.each(function()
    {
        let items   = [];
        let $this   = $(this).zui('picker');
        let options = $this.options;
        let value   = $this.$.state.value;
        if(options.readonly) return;

        usersPickerItems.forEach(function(userItem)
        {
            if(!users.includes(userItem.value)) items.push(userItem);
            if(value != '' && value == userItem.value) items.push(userItem);
        })
        $this.render({items: items});
    });
}
