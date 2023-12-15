function hideSingleField(hiddenFields)
{
    if(typeof hiddenFields == 'undefined') return false;

    hiddenFields.forEach(function(field)
    {
        let $this = $('form [name^="' + field + '"]');
        if($this.length == 0) return;

        let $formGroup    = $this.closest('.form-group');
        let $inputGroup   = $this.closest('.input-group');
        let $inputControl = $this.closest('.input-control');
        if($inputGroup.length == 1)
        {
            $prev = $this.prev();
            if($prev.hasClass('input-group-addon')) $prev.addClass('hidden');

            $this.addClass('hidden');
            if($this.hasClass('pick-value'))
            {
                $pickBox = $this.closest('.pick').parent();
                $pickBox.addClass('hidden');
                $pickBox.closest('.form-group').addClass('hidden');

                $prev = $pickBox.prev();
                if($prev.hasClass('input-group-addon')) $prev.addClass('hidden');
            }

            if($inputControl.length == 1)
            {
                if($inputControl.prev().hasClass('input-group-addon')) $inputControl.prev().addClass('hidden');
                if($inputControl.hasClass('has-suffix')) $inputControl.addClass('hidden');
            }
        }
        else
        {
            $formGroup.addClass('hidden');
        }
    })

    $('form .form-row').each(function()
    {
        var $this = $(this);
        if($this.find('.form-group.hidden').length > 0 && $this.find('.form-group:not(.hidden)').length == 0) $this.addClass('hidden');
    });
}

function hideBatchField(hiddenFields)
{
    if(typeof hiddenFields == 'undefined') return false;
    if(hiddenFields.length == 0) return false;

    if(hiddenFields.includes('source')) hiddenFields.push('sourceNote');
    batchForm = $('th.form-batch-head').closest('.form-batch').zui('batchForm');
    batchForm.toggleCols(hiddenFields, false);
}

if(typeof hiddenFields != 'undefined')
{
    if(typeof formBatch == 'undefined' || !formBatch)
    {
        setTimeout(function(){hideSingleField(hiddenFields)}, 300);
    }
    else
    {
        setTimeout(function(){hideBatchField(hiddenFields)}, 800);
    }
}
