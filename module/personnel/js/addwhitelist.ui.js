window.renderRowData = function($row, index, row)
{
    if(row == undefined) return;

    $row.find('[data-name="account"]').find('.picker-box').on('inited', function(e, info)
    {
        let $account = info[0];
        let isAppend = typeof(row.isAppend) != 'undefined' && row.isAppend;

        items = userItems;
        if(isAppend) items = $account.options.items;
        $account.render({items: items, required: true, readonly: !isAppend});
        $account.$.setValue(row.account);
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
