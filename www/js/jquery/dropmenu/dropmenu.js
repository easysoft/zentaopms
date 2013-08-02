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
    if(position == 'bottom')
    {
        topOffset  = offset.top + $('#' + currentID).parent().outerHeight();
        leftOffset = offset.left;
        $('#' + currentID + 'Menu').css({top: topOffset, left: leftOffset});
    }
    $('#' + currentID + 'Menu').toggle();
}

$(function()
{
    $(document).click(function(e){if($(e.target).parent(".dropButton").length==0){ $("div[id$='ActionMenu']").hide();}})
})
