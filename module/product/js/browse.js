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
 * @param  formName   $formName 
 * @param  actionName $actionName 
 * @param  actionLink $actionLink 
 * @access public
 * @return void
 */
function changeAction(formName, actionName, actionLink)
{
    $('#' + formName).attr('action', actionLink).submit();
}

$(function()
{
    if(browseType == 'bysearch') ajaxGetSearchForm();
    $(function(){$('.iframe').colorbox({width:900, height:500, iframe:true, transition:'none', onCleanup:function(){parent.location.href=parent.location.href;}});})
})
