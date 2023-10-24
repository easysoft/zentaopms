window.handleCheckboxGroupClick = function(event)
{
    const $target = $(event.target);
    const $checkboxGroup = $target.closest('.checkbox-group');
    if($target.closest('.checkbox-title').length > 0)
    {
        const $checkboxTitle = $target.closest('.checkbox-title');
        $checkboxGroup
            .find('.checkbox-child')
            .prop('checked', $checkboxTitle.prop('checked'));
        return;
    }

    if($target.closest('.checkbox-child').length > 0)
    {
        let checkedCount = 0;
        const $checkboxChildren = $checkboxGroup.find('.checkbox-child');
        $checkboxChildren.each((_i, input) =>
        {
            if(input.checked === true) checkedCount++;
        });

        const checkboxTitle = $checkboxGroup.find('.checkbox-title')[0];
        if(checkedCount === 0)
        {
            checkboxTitle.checked = false;
            checkboxTitle.indeterminate = false;
        }
        else if(checkedCount === $checkboxChildren.length)
        {
            $checkboxGroup.find('.checkbox-title').prop('checked', true);
            checkboxTitle.checked = true;
            checkboxTitle.indeterminate = false;
        }
        else
        {
            checkboxTitle.checked = false;
            checkboxTitle.indeterminate = true;
        }
    }
}
