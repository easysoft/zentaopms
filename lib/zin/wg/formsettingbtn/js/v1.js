function closeCustomPopupMenu(e)
{
    $(e.target).closest('menu').removeClass('show');
}

function revertDefaultFields(e)
{
    $.get($(e.target).closest('button').data('url'), function(data, status)
    {
        const customFields = [];
        const showFields   = [];
        e.target.closest('form').querySelectorAll('input').forEach(function(field)
        {
            /* Gather all custom fields. */
            customFields.push(field.value);
            if(field.value === 'source') customFields.push('sourceNote');

            $(field).prop('checked', false);
            if(!$(field).data('default')) return;

            /* Gather checked fields to be visible. */
            $(field).prop('checked', true);
            showFields.push(field.value);
            if(field.value === 'source') showFields.push('sourceNote');
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
        var $this = $('form [name^="' + field + '"]');
        if($this.length == 0) return;

        var hidden      = !showFields.includes(field);
        var $inputGroup = $this.closest('.input-group');
        var $formGroup  = $this.closest('.form-group');
        if($inputGroup.length == 1)
        {
            $prev = $this.prev();
            if($prev.hasClass('input-group-addon')) $prev.toggleClass('hidden', hidden);

            $this.toggleClass('hidden', hidden);
            if($this.hasClass('pick-value'))
            {
                $pickBox = $this.closest('.pick').parent();
                $pickBox.toggleClass('hidden', hidden);

                $prev = $pickBox.prev();
                if($prev.hasClass('input-group-addon')) $prev.toggleClass('hidden', hidden);
            }
        }
        else
        {
            $formGroup.toggleClass('hidden', hidden);
        }
    })

    $('form .form-row').each(function()
    {
        var $this = $(this);
        $this.removeClass('hidden');
        if($this.find('.form-group.hidden').length > 0 && $this.find('.form-group:not(.hidden)').length == 0) $this.addClass('hidden');
    });
}

function toggleBatchField(customFields, showFields)
{
    if(typeof customFields == 'undefined') return false;

    customFields.forEach(function(field)
    {
        var hidden = !showFields.includes(field);
        $('th.form-batch-head[data-name="' + field + '"]').toggleClass('hidden', hidden);
        $('td.form-batch-control[data-name="' + field + '"]').toggleClass('hidden', hidden);
        $($('template.form-batch-template')[0].content).find('td.form-batch-control[data-name="' + field + '"]').toggleClass('hidden', hidden);
    });
}
