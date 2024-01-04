window.changeMode = function()
{
    const mode = $('[name=mode]:checked').val();
    $('#otherLaneBox').toggleClass('hidden', mode != 'sameAsOther');
}

window.changeLaneType = function(e)
{
    const laneType = e.target.value;
    const link     = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=' + laneType);

    $.get(link, function(data)
    {
        data = JSON.parse(data);
        $('[name=otherLane]').zui('picker').$.setValue('');
        $('[name=otherLane]').zui('picker').render(data);
    })
}
