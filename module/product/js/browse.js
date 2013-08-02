/* Browse by module. */
function browseByModule()
{
    $('#treebox').removeClass('hidden');
    $('.divider').removeClass('hidden');
    $('#querybox').addClass('hidden');
    $('#featurebar .active').removeClass('active');
    $('#bymoduleTab').addClass('active');
}

/**
 * Change form action.
 * 
 * @param  url  $actionLink 
 * @param  bool $hiddenwin 
 * @access public
 * @return void
 */
function changeAction(actionLink, hiddenwin)
{
    if(hiddenwin) $('form').attr('target', 'hiddenwin');
    $('form').attr('action', actionLink).submit();
}

$(function()
{
    if(browseType == 'bysearch') ajaxGetSearchForm();
})
