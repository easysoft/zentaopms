function changeRegion(event)
{
    const regionID = $(event.target).val();
    if(!regionID)
    {
        const $lanePicker = $('#moveCardForm').find('[name=lane]').zui('picker');
        $lanePicker.render({items: [], disabled: true});
        $lanePicker.$.setValue('');
        const $columnPicker = $('#moveCardForm').find('[name=column]').zui('picker');
    }
}
