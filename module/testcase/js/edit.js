/**
 * Get story list.
 * 
 * @param  string $module 
 * @access public
 * @return void
 */
function getList()
{
    productID = $('#product').get(0).value;
    storyID   = $('#story').get(0).value;
    link = createLink('search', 'select', 'productID=' + productID + '&projectID=0&module=story&moduleID=' + storyID);
    $('#storyListIdBox a').attr("href", link);
}

$(document).ready(function()
{
    $("#story").chosen(defaultChosenOptions);
});


/**
 * Delete linked case.
 *
 * @param  int $caseID
 * @param  int $deleteCase
 * @access public
 * @return void
 */
function deleteLinkedCase(caseID, deleteCase)
{
    deleteLink = createLink('testcase', 'ajaxDeleteLinkedCase', 'caseID=' + caseID + '&deleteCase=' + deleteCase);
    $('#linkCaseBox').load(deleteLink);
}

/**
 * Load linked cases.
 *
 * @param  int    $caseID
 * @param  string $linkedCases
 * @access public
 * @return void
 */
function loadLinkedCases(caseID, linkedCases)
{
    caseLink = createLink('testcase', 'ajaxGetLinkedCases', 'caseID=' + caseID + '&linkedCases=' + linkedCases);
    $('#linkCaseBox').load(caseLink);
}
