window.renderRowData = function($row, index, row)
{
    if(row != undefined && row.isAppend == undefined)
    {
        $row.find('td[data-name="account"] > *').attr('readonly', 'readonly');
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
    var copyID = $('#object').val();
    var deptID   = $('#dept').val();
    var link     = $.createLink(module, moduleMethod, 'objectID=' + objectID + '&deptID=' + deptID + '&copyID=' + copyID + '&objectType=' + objectType + '&module=' + module);
    if(module == 'program') link = $.createLink(module, moduleMethod, 'objectID=' + objectID + '&deptID=' + deptID + '&copyID=' + copyID + '&programID=' + programID + '&from=' + from);

    loadPage(link, '.panel-body');
}
