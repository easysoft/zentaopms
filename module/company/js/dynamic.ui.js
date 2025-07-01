/**
 * When search item change.
 *
 * @access public
 * @return void
 */
function changeItem()
{
    var userID      = $('[name="user"]').val();
    var productID   = $('[name="product"]').length ? $('[name="product"]').val() : 0;
    var projectID   = $('[name="project"]').val();
    var executionID = $('[name="execution"]').val();

    var type = browseType == 'bysearch' ? 'all' : browseType;
    link = $.createLink('company', 'dynamic', 'browseType=' + type + '&param=&recTotal=0&date=&direction=no&userID=' + userID + '&productID=' + productID + '&projectID=' + projectID + '&executionID=' + executionID);
    loadPage(link);
}

function toggleCollapse()
{
    $(this).parent().toggleClass('collapsed');
}
