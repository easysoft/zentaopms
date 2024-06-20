window.changeRegion = function(e)
{
    const $region  = $(e.target);
    const regionID = $region.val();
    const laneLink = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=story&field=lane');
    $.getJSON(laneLink, function(data)
    {
        const laneID = data.items.length > 0 ? data.items[0].value : '';
        $region.closest('tr').find('[name^=lane]').zui('picker').render({items: data.items});
        $region.closest('tr').find('[name^=lane]').zui('picker').$.setValue(laneID);
    });

}
