/* Swtich to search module. */
function browseBySearch(active)
{
    $('#querybox').removeClass('hidden');
    $('.divider').addClass('hidden');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').addClass('active');
    $('#bymoduleTab').removeClass('active');
}

/**
 * Confirm batch delete cases.
 * 
 * @param  string $actionLink 
 * @access public
 * @return void
 */
function confirmBatchDelete(actionLink)
{
    if(confirm(batchDelete)) setFormAction(actionLink);
    return false;
}

$(document).ready(function()
{
    $('#' + browseType + 'Tab').addClass('active');
    $('#module' + moduleID).addClass('active'); 
    if(browseType == 'bysearch') ajaxGetSearchForm();

    setModal4List('runCase', 'caseList');
});
