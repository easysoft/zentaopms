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
    if($('#caseList thead th.c-title').width() < 150) $('#caseList thead th.c-title').width(150);

    if(flow == 'onlyTest')
    {
        $('#subNavbar > .nav li[data-id=' + browseType + ']').addClass('active');

        if(browseType == 'bysuite')
        {
            var $moreSuite = $('#subNavbar > .nav > li[data-id=bysuite]');
            if($moreSuite.find('.dropdown-menu').children().length)
            {
                $moreSuite.find('.dropdown-menu').children().each(function()
                {
                    if($(this).data('id') == suiteID) $(this).addClass('active');
                });
            }
        }
    }
});
