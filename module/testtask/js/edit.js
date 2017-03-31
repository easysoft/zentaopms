/**
 * Load project related builds
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadProjectRelated(projectID)
{
    loadProjectBuilds(projectID);
}

/**
 * Load project builds.
 * 
 * @param  int $projectID 
 * @access public
 * @return void
 */
function loadProjectBuilds(projectID)
{
    selectedBuild = $('#build').val();
    if(!selectedBuild) selectedBuild = 0;
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + $('#product').val() + '&varName=testTaskBuild&build=' + selectedBuild);
    $('#buildBox').load(link, function(){$('#build').chosen(defaultChosenOptions);});
}

$(function()
{
    var adjustPriBoxWidth = function()
    {
        var boxWidth   = $('#ownerAndPriBox').width();
        var beginWidth = $("input[name='begin']").outerWidth();
        var addonWidth = $('#ownerAndPriBox .input-group-addon').outerWidth();
        $('#pri').css('width', boxWidth - beginWidth -addonWidth);
    };
    adjustPriBoxWidth();//Adjust testtask pri box width.
})
