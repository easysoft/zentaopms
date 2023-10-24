$(document).ready(function()
{
    if(browseType == 'bysearch') $.toggleQueryBox(true);
    if($('#caseList thead th.w-title').width() < 150) $('#caseList thead th.w-title').width(150);
});

/**
 * Get checked items.
 *
 * @access public
 * @return array
 */
function getCheckedItems()
{
    var checkedItems = [];
    $('#casesForm [name^=caseIDList]:checked').each(function(index, ele)
    {
        checkedItems.push($(ele).val());
    });
    return checkedItems;
};
