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
    var link = createLink('kanban', 'ajaxGetContactUsers', "listID=" + value);
    $.post(link, function(data)
    {
        $('#team').replaceWith(data);
        $('#team_chosen').remove();
        $('#team').chosen();
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
