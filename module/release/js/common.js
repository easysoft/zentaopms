$(document).ready(function()
{
    $("a.preview").modalTrigger({width:1000, type:'iframe'});
})

/**
 * Load branch builds.
 * 
 * @param  int $branchID 
 * @access public
 * @return void
 */
function loadBranchBuilds(branchID)
{
    if(page == 'create')
    {
        oldReleasedBuild = $('#build').val() ? $('#build').val() : 0;

        link = createLink('build', 'ajaxGetBranchBuilds', 'productID=' + productID + '&branchID=' + branchID + '&operation=createRelease&build=' + oldReleasedBuild);
        $('#buildBox').load(link, function(){$('#build').chosen(defaultChosenOptions);}); 
    }
    else
    {
        link = createLink('build', 'ajaxGetBranchBuilds', 'productID=' + productID + '&branchID=' + branchID + '&operation=editRelease&build=' + oldReleasedBuild);
        $('#buildBox').load(link, function(){$('#build').chosen(defaultChosenOptions);}); 
    }
}
