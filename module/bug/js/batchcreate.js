$(function()
{
    $(".chosenBox select").chosen(defaultChosenOptions);
})

/**
 * Load project builds 
 * 
 * @param  int $productID 
 * @param  int $projectID 
 * @param  int $index 
 * @access public
 * @return void
 */
function loadProjectBuilds(productID, projectID, index)
{
    if(projectID)
    {
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + "&varName=openedBuilds&build=''&index=" + index);
    }
    else
    {
        link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + "&varName=openedBuilds&build=''&index=" + index);
    }

    $.get(link, function(builds)
    {
        $('#buildBox' + index).html(builds);
        $('#openedBuilds' + index + '_chosen').remove();
        $('#buildBox' + index + ' select').removeClass('select-3');
        $('#buildBox' + index + ' select').addClass('select-1');
        $('#buildBox' + index + ' select').attr('id', 'openedBuilds[' + index + '][]');
        $('#buildBox' + index + ' select').chosen(defaultChosenOptions);
    });
}
