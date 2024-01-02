$(function()
{
    $('.querybox-toggle').parent().addClass('active');

    $(document).on('click', '.chosen-with-drop', function()
    {
        var select = $(this).prev('select');
        if($(select).val() == 'ditto')
        {
            var index = $(select).closest('td').index();
            var row   = $(select).closest('tr').index();
            var table = $(select).closest('tr').parent();
            var value = '';
            for(i = row - 1; i >= 0; i--)
            {
                value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
                if(value != 'ditto') break;
            }
            $(select).val(value);
            $(select).trigger("chosen:updated");
        }
    });
    $('.pager a.pager-item').attr('data-app', app);
})

/**
 * Reload.
 *
 * @param  int   $libID
 * @access public
 * @return void
 */
function reload(libID)
{
    link = createLink(rawModule,'importFromLib','productID='+ productID + '&branch=' + branch + '&libID='+libID);
    location.href = link;
}

/**
 * Update modules.
 *
 * @param  int $productID
 * @param  int $branch
 * @param  int $caseID
 * @access public
 * @return void
 */
function updateModules(productID, branch, caseID)
{
    if(typeof(branch) == 'undefined') branch = 0;

    var moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    var tr = $('#module' + caseID).closest('tr');
    if(branch !== 'ditto')
    {
        loadModules(tr, caseID, moduleLink, undefined, branch);
        tr.nextAll().each(function()
        {
            var nextCaseID = $(this).attr('id');
            var nextBranch = $('#branch' + nextCaseID + ' option:selected').val();
            if(nextBranch !== 'ditto') return false;
            var nextTr = $('#module' + nextCaseID).closest('tr');
            loadModules(nextTr, nextCaseID, moduleLink, true, branch);
        });
    }
    else
    {
        var branchID = '';
        tr.prevAll().each(function()
        {
            var prevCaseID = $(this).attr('id');
            var prevBranch = $('#branch' + prevCaseID + ' option:selected').val();
            if(prevBranch !== 'ditto')
            {
                branchID = prevBranch;
                return false;
            }
        });
        link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branchID + '&rootModuleID=0&returnType=html&fieldID=&needManage=true');
        loadModules(tr, caseID, link, true, branchID);
        tr.nextAll().each(function()
        {
            var nextCaseID = $(this).attr('id');
            var nextBranch = $('#branch' + nextCaseID + ' option:selected').val();
            if(nextBranch !== 'ditto') return false;
            var nextTr = $('#module' + nextCaseID).closest('tr');
            loadModules(nextTr, nextCaseID, link, true, branchID);
        });
    }
}

/**
 * Load modules.
 *
 * @param  object  $tr
 * @param  int     $caseID
 * @param  string  $link
 * @param  boolean $isAddDitto
 * @param  int     $branch
 * @access public
 * @return void
 */
function loadModules(tr, caseID, link, isAddDitto, branch)
{
    var isAddDitto = (typeof(isAddDitto) === 'undefined') ? false : true;

    $('#module' + caseID).parent('td').load(link, function(data)
    {
        if(canImportModules[branch][caseID] != undefined && Object.keys(canImportModules[branch][caseID]).length > 0)
        {
            $('tr select#module').children().each(function()
            {
                moduleID = $(this).val();
                if(canImportModules[branch][caseID][moduleID] == undefined)
                {
                    $(this).remove();
                }
            })
        }

        tr.find('#module').chosen();
        tr.find('#module').attr({"id": 'module' + caseID, "name": 'module[' + caseID + ']'});
        tr.find('#module' + caseID).removeAttr('onchange');
        if(isAddDitto == true) addDittoOption(caseID);
    });
}

/**
 * add ditto option in select.
 *
 * @param  int    $caseID
 * @access public
 * @return void
 */
function addDittoOption(caseID)
{
    $('#module' + caseID).append("<option value='ditto' selected='selected'>" + ditto + "</option>");
    $('#module' + caseID).trigger('chosen:updated');
}
