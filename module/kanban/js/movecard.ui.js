function changeRegion(event)
{
    const regionID = $(event.target).val();
    if(!regionID)
    {
        const $lanePicker = $('#moveCardForm').find('[name=lane]').zui('picker');
        $lanePicker.render({items: [], disabled: true});
        $lanePicker.$.setValue('');
        const $columnPicker = $('#moveCardForm').find('[name=column]').zui('picker');
        $columnPicker.render({items: [], disabled: true});
        $columnPicker.$.setValue('');
        return;
    }

    const laneLink = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID);
    $.getJSON(laneLink, function(data)
    {
        if(!data || !data.items) return;

        const $lanePicker = $('#moveCardForm').find('[name=lane]').zui('picker');
        const oldLane     = $('#moveCardForm').find('[name=lane]').val();
        $lanePicker.render({items: data.items, disabled: false});
        if(oldLane) $lanePicker.$.setValue(oldLane);
    });
}

function changeLane(event)
{
    const laneID = $(event.target).val();
    if(!laneID)
    {
        const $columnPicker = $('#moveCardForm').find('[name=column]').zui('picker');
        $columnPicker.render({items: [], disabled: true});
        return;
    }

    const columnLink = $.createLink('kanban', 'ajaxGetColumns', 'laneID=' + laneID);
    $.getJSON(columnLink, function(data)
    {
        if(!data) return;

        const $columnPicker = $('#moveCardForm').find('[name=column]').zui('picker');
        const oldColumn     = $('#moveCardForm').find('[name=column]').val();
        $columnPicker.render({items: data, disabled: false});
        if(oldColumn) $columnPicker.$.setValue(oldColumn);
    });
}
