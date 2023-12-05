window.renderRowData = function($row, index, row)
{
    if(row != undefined && row.isAppend == undefined)
    {
        $row.find('[data-name="account"]').find('.picker-box').on('inited', function(e, info)
        {
            let $account = info[0];
            $account.render({items: userItems});
            $account.$.setValue(row.account);
            $account.render({required: true, readonly: true});
        });
    }
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
