function closeCustomPopupMenu(e)
{
    $('#formSettingBtn-toggle').trigger('click');
}

function cancelFormSetting(e)
{
    $.get($(e.target).closest('button').data('url'), function(data)
    {
        e.target.closest('form').querySelectorAll('input').forEach(function(field)
        {
            const checked = `,${data},`.indexOf(`,${$(field).val()},`) >=0;
            $(field).prop('checked', checked);
        });
    });

    closeCustomPopupMenu(e);
}

function revertDefaultFields(e)
{
    $.getJSON($(e.target).closest('button').data('url'), function(response, status)
    {
        const customFields = response.customFields.split(',');
        const showFields   = response.showFields.split(',');
        showFields.forEach(function(field)
        {
            if(!field) return true;

            var $fieldCheckbox = $(e.target).closest('form').find('input[type=checkbox][value="' + field + '"]');
            if($fieldCheckbox.length == 0) return false;
            $fieldCheckbox.prop('checked', true);
        });

        hideAndShowFormFields(customFields, showFields);
        closeCustomPopupMenu(e);
    });
}

function onSubmitFormtSetting(e)
{
    const customFields = [];
    const showFields   = [];
    const $form        = $(e.target).closest('form');

    $.post($form.attr('action'), new FormData($form[0]), function(result)
    {
        $form.find('input[type="checkbox"]').each(function()
        {
            /* Gather all custom fields. */
            $field = $(this);
            customFields.push($field.val());
            if($field.val() === 'source') customFields.push('sourceNote');

            if(!$field.prop('checked')) return;

            /* Gather checked fields to be visible. */
            showFields.push($field.val());
            if($field.val() === 'source') showFields.push('sourceNote');
        });

        hideAndShowFormFields(customFields, showFields);
        closeCustomPopupMenu(e);
    });
}

function hideAndShowFormFields(customFields, showFields)
{
    if(typeof formBatch == 'undefined' || !formBatch) return toggleSingleField(customFields, showFields);
    if(typeof formBatch != 'undefined' && formBatch)  return toggleBatchField(customFields, showFields);
}

function toggleSingleField(customFields, showFields)
{
    if(typeof customFields == 'undefined') return false;

    customFields.forEach(function(field)
    {
        let $field = $('form [name^="' + field + '"]');
        if($field.length == 0) return;

        let hidden        = !showFields.includes(field);
        let $formRow      = $field.closest('.form-row');
        let $formGroup    = $field.closest('.form-group');
        let $inputGroup   = $field.closest('.input-group');
        let $inputControl = $field.closest('.input-control');
        if($inputGroup.length == 1)
        {
            $prev = $field.prev();
            if($prev.hasClass('input-group-addon')) $prev.toggleClass('hidden', hidden);

            $field.toggleClass('hidden', hidden);
            if($field.hasClass('pick-value'))
            {
                $pickBox = $field.closest('.pick').parent();
                $pickBox.toggleClass('hidden', hidden);

                $prev = $pickBox.prev();
                if($prev.hasClass('input-group-addon')) $prev.toggleClass('hidden', hidden);

                $next = $pickBox.next();
                if($next.hasClass('input-group-addon')) $next.toggleClass('hidden', hidden);
            }

            if($inputControl.length == 1)
            {
                if($inputControl.hasClass('has-suffix'))
                {
                    if($inputControl.prev().hasClass('input-group-addon')) $inputControl.prev().toggleClass('hidden', hidden);
                    $inputControl.toggleClass('hidden', hidden);
                }
            }

            if($inputGroup.prev().hasClass('form-label')) $inputGroup.prev().toggleClass('hidden', hidden);
            $inputGroup.toggleClass('hidden', $inputGroup.children().length == $inputGroup.children('.hidden').length);
            $formGroup.toggleClass('hidden', $formGroup.children().length == $formGroup.children('.hidden').length);
        }
        else
        {
            $formGroup.toggleClass('hidden', hidden);
        }
        $formRow.toggleClass('hidden', $formRow.children().length == $formRow.children('.hidden').length);
    });
}

function toggleBatchField(customFields, showFields)
{
    if(typeof customFields == 'undefined') return false;

    let hiddenFields = [];
    let shownFields  = [];
    customFields.forEach(function(field)
    {
        if($('th.form-batch-head[data-name="' + field + '"]').length == 0) return true;

        let hidden = !showFields.includes(field);
        $('th.form-batch-head[data-name="' + field + '"]').toggleClass('hidden', hidden);
        $('td.form-batch-control[data-name="' + field + '"]').toggleClass('hidden', hidden);
        $($('template.form-batch-template')[0].content).find('td.form-batch-control[data-name="' + field + '"]').toggleClass('hidden', hidden);
        if(field === 'source')
        {
            $('th.form-batch-head[data-name="sourceNote"]').toggleClass('hidden', hidden);
            $('td.form-batch-control[data-name="sourceNote"]').toggleClass('hidden', hidden);
            $($('template.form-batch-template')[0].content).find('td.form-batch-control[data-name="sourceNote"]').toggleClass('hidden', hidden);
        }

        hidden ? hiddenFields.push(field) : shownFields.push(field);
        if(field === 'source') hidden ? hiddenFields.push('sourceNote') : shownFields.push('sourceNote');
    });

    batchForm = $('th.form-batch-head').closest('.form-batch').zui('batchForm');
    if(hiddenFields.length > 0) batchForm.toggleCols(hiddenFields, false);
    if(shownFields.length > 0)  batchForm.toggleCols(shownFields, true);
}
