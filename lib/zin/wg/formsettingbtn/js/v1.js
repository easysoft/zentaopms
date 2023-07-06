function closeCustomPopupMenu(e)
{
    $(e.target).closest('menu').removeClass('show');
}

function revertDefaultFields(e)
{
    $.get($(e.target).closest('button').data('url'), function(data, status)
    {
        if(status === 'success')
        {
            const customFields = new Array();
            const showFields   = new Array();
            e.target.closest('form').querySelectorAll('input').forEach(function(field)
            {
                /* Gather all custom fields. */
                customFields.push(field.value);
                if(field.value === 'source')
                {
                    customFields.push('sourceNote');
                }
                !$(field).prop('checked', false);

                if(!$(field).data('default')) return;

                /* Gather checked fields to be visible. */
                !$(field).prop('checked', true);
                showFields.push(field.value);
                if(field.value === 'source')
                {
                    showFields.push('sourceNote');
                }
            });

            hideAndShowFormFields(customFields, showFields);
        }
    });

    return false;
}

function onSubmitFormtSetting(e)
{
    const customFields = new Array();
    const showFields   = new Array();

    const formFields = e.target.querySelectorAll('input');
    formFields.forEach(function(field)
    {
        /* Gather all custom fields. */
        customFields.push(field.value);
        if(field.value === 'source')
        {
            customFields.push('sourceNote');
        }

        if(!field.checked) return;

        /* Gather checked fields to be visible. */
        showFields.push(field.value);
        if(field.value === 'source')
        {
            showFields.push('sourceNote');
        }
    });

    hideAndShowFormFields(customFields, showFields);
}

function hideAndShowFormFields(hiddenFields, showFields)
{
    /* Hide all custom fields. */
    hiddenFields.forEach(function(field)
    {
        $(document.querySelectorAll(`[data-name=${field}]`)).addClass('hidden');
    });

    /* Show checked fields. */
    showFields.forEach(function(field)
    {
        const elements = document.querySelectorAll(`[data-name=${field}]`);

        /* For ditto button. */
        elements.forEach(function(ele)
        {
            const dittoEle = ele.querySelector('.has-suffix')
            if(!dittoEle) return;

            $(dittoEle).attr('style', '--input-control-suffix: 42px;');
        });

        $(elements).removeClass('hidden');
    });
}
