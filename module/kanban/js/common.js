/**
 * Set mailto.
 *
 * @param  string $field
 * @param  int    $value
 * @access public
 * @return void
 */
function setMailto(field, value)
{
    var link = createLink('kanban', 'ajaxGetContactUsers', 'field=' + field + '&listID=' + value);
    $.post(link, function(data)
    {
        $('#' + field).replaceWith(data);
        $('#' + field + '_chosen').remove();
        $('#' + field).siblings('.picker').remove();
        $('#' + field).picker();
    })
}

/**
 * Initialize custom color selector.
 *
 * @access public
 * @return void
 */
function initColorPicker()
{
    var selectedColor = $().val();
    if(selectedColor && $.inArray(selectedColor.toUpperCase(), colorList) == -1) colorList.unshift(selectedColor);
    colorList.forEach(function(color, index)
    {
        var itemClass = color.toUpperCase() == $('input[name=color]').val().toUpperCase() ? 'color-picker-item checked' : 'color-picker-item';
        var colorItem = "<div class='" + itemClass  + "' data-color='" + color  + "' style='background: " + color  + "'>";
        colorItem += "<i class='icon icon-check'></i>";
        colorItem += "</div>";
        $('#color-picker').append(colorItem);
    });

    $('.color-picker-item').click(function()
    {
        var color = $(this).attr('data-color');
        $('input[name=color]').val(color);
        $(this).addClass('checked');
        $(this).siblings().removeClass('checked');
    })
}

/**
 * Reload object list.
 *
 * @param  int $targetID
 * @access public
 * @return void
 */
function reloadObjectList(targetID)
{
    location.href = createLink('kanban', methodName, 'kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID + '&targetID=' + targetID);
}

/**
 * Set target lane ID.
 *
 * @param  int $targetLaneID
 * @access public
 * @return void
 */
function setTargetLane(targetLaneID)
{
    $('#targetLane').val(targetLaneID);
}

/**
 * Jump to the view page.
 *
 * @param  string $module
 * @param  int    $objectID
 * @access public
 * @return void
 */
function locateView(module, objectID)
{
    var dataApp = 'kanban';
    if(module == 'productplan' || module == 'release') dataApp = 'product';
    if(module == 'execution') dataApp = 'execution';
    if(module == 'build') dataApp = 'project';
    parent.$.apps.open(createLink(module, 'view', 'objectID=' + objectID), dataApp);
}

/**
 * When type change.
 *
 * @param  string type
 * @access public
 * @return void
 */
function changeType(type)
{
    if(type == 'private')
    {
        $('#ownerBox').addClass('hidden');
        $('#teamBox').addClass('hidden');
        $('#whitelistBox').removeClass('hidden');
    }
    else
    {
        $('#ownerBox').removeClass('hidden');
        $('#teamBox').removeClass('hidden');
        $('#whitelistBox').addClass('hidden');
    }
}

/**
 * Load all users.
 *
 * @access public
 * @return void
 */
function loadAllUsers()
{
    var link = createLink('kanban', 'ajaxLoadUsers', 'spaceID=0&field=owner&selectedUser=' + $('#owner').val() + "&type=all");

    $.get(link, function(data)
    {
        $('#owner').replaceWith(data);
        $('#owner' + "_chosen").remove();
        $('#owner').next('.picker').remove();
        $('#owner').chosen();
    });
}

/**
 * The owners that loads kanban.
 *
 * @oaram  int    spaceID
 * @access public
 * @return void
 */
function loadOwners(spaceID)
{
    var link = createLink('kanban', 'ajaxLoadUsers', 'spaceID='+ spaceID + '&field=owner&selectedUser=' + $('#owner').val());

    $.get(link, function(data)
    {
        $('#owner').replaceWith(data);
        $('#owner' + "_chosen").remove();
        $('#owner').next('.picker').remove();
        $('#owner').chosen();
    });
}
