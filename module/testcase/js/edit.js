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
    
    initSteps();
});


/**
 * Unlink related case.
 *
 * @param  int $caseID
 * @param  int $case2Unlink
 * @access public
 * @return void
 */
function unlinkCase(caseID, case2Unlink)
{
    link = createLink('testcase', 'unlinkCase', 'caseID=' + caseID + '&case2Unlink=' + case2Unlink);
    $.get(link, function(data)
    {
        if(data == 'success') $('#linkCaseBox').load(createLink('testcase', 'ajaxGetLinkCases', 'caseID=' + caseID));
    });
}

/**
 * Load linkCases.
 *
 * @param  int    $caseID
 * @access public
 * @return void
 */
function loadLinkCases(caseID)
{
    caseLink = createLink('testcase', 'ajaxGetLinkCases', 'caseID=' + caseID);
    $('#linkCaseBox').load(caseLink);
}

/**
 * Load lib modules.
 * 
 * @param  int $libID 
 * @access public
 * @return void
 */
function loadLibModules(libID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'libID=' + libID + '&viewtype=caselib&branch=0&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    $('#moduleIdBox').load(link, function()
    {
        $(this).find('select').chosen(defaultChosenOptions)
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>")
    });
}
