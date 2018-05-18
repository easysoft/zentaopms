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

$(function()
{
    if(browseType == 'bysearch') ajaxGetSearchForm();
    if($('#caseList thead th.w-title').width() < 150) $('#caseList thead th.w-title').width(150);

    if(flow == 'onlyTest')
    {
        toggleSearch();
        $('.export').modalTrigger({width:650, type:'iframe', afterShown: setCheckedCookie})

        $('#subNavbar > .nav > li').removeClass('active');
        $('#subNavbar > .nav > li[data-id=' + browseType + ']').addClass('active');
    }
});

function setQueryBar(queryID, title)
{
    $('#bysearchTab').before("<li id='QUERY" + queryID + "Tab' class='active'><a href='" + createLink('testcase', 'browse', "productID=" + productID + "&branch=" + branch + "&browseType=bysearch&param=" + queryID) + "'>" + title + "</a></li>");
}
