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

/**
 * Toggle sub menu. 
 * 
 * @param  string $currentID 
 * @param  string position = 'right' | 'top'
 * @param  bool entry
 * @access public
 * @return void
 */
function toggleSubMenu(currentID, position, menuIndex)
{
    if(typeof(position)  == 'undefined') position  = 'right';
    if(typeof(menuIndex) == 'undefined') menuIndex = 1;
    if(menuIndex)
    {
        currentMenu = $('#' + currentID).parent().parent().parent().attr('id');
        $('#' + currentID + 'Menu').bind('mouseover', function()
        {
            $('#' + currentMenu).show();
            return $('#' + currentID + 'Menu').show()
        });

        $('#' + currentID).parent().bind('mouseleave', function()
        {
            return $('#' + currentID + 'Menu').hide()
        });

        $('#' + currentID + 'Menu').bind('mouseleave', function()
        {
            return $('#' + currentID + 'Menu').hide()
        });
    }

    var offset     = $('#' + currentID).offset();
    var topOffset  = 0;
    var leftOffset = 0;
    if(position == 'top')
    {
        topOffset  = offset.top - $('#' + currentID + 'Menu').height() + 'px';
        leftOffset = offset.left;
        $('#' + currentID + 'Menu').css({top: topOffset, left: leftOffset});
    }
    if(position == 'right')  
    {
        if(menuIndex == 1)
        {
            topOffset  = offset.top - $('#' + currentID + 'Menu').height() + $('#' + currentID).parent().height() + "px";
            leftOffset = offset.left + $('#' + currentID).parent().width();
            $('#' + currentID + 'Menu').css({top: topOffset, left: leftOffset});
        }
        else if(menuIndex == 2)
        {
            currentMenu = $('#' + currentID).parent().parent().parent().attr('id');
            rootID      = currentMenu.replace('Menu', '');
            topOffset   = $('#' + rootID).offset().top - $('#' + currentID + 'Menu').height() + 20;
            leftOffset  = $('#' + rootID).offset().left + $('#' + rootID).parent().width() + $('#' + currentMenu).width();
            $('#' + currentID + 'Menu').css({top: topOffset, left:leftOffset});
        }
    }
    $('#' + currentID + 'Menu').toggle();
}

function ajaxShowMenu(currentID, productID)
{
    $.get(createLink('product', 'ajaxGetPlans', "productID=" + productID + "&plan=0&showMenu=true"), function(data){ $('#' + currentID + 'Menu').html(data);});
    toggleSubMenu(currentID);
}

$(function()
{
    if(browseType == 'bysearch') ajaxGetSearchForm();
    setModal4List('iframe', 'storyList');
})
