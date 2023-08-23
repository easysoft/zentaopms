function hideSingleField(hiddenFields)
{
    if(typeof hiddenFields == 'undefined') return false;

    hiddenFields.forEach(function(field)
    {
        var $this = $('form [name^="' + field + '"]');
        if($this.length == 0) return;

        $inputGroup = $this.closest('.input-group');
        $formGroup  = $this.closest('.form-group');
        if($inputGroup.length == 1)
        {
            $prev = $this.prev();
            if($prev.hasClass('input-group-addon')) $prev.addClass('hidden');

            $this.addClass('hidden');
            if($this.hasClass('pick-value'))
            {
                $pickBox = $this.closest('.pick').parent();
                $pickBox.addClass('hidden');

                $prev = $pickBox.prev();
                if($prev.hasClass('input-group-addon')) $prev.addClass('hidden');
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

    hiddenFields.forEach(function(field)
    {
        $('th.form-batch-head[data-name="' + field + '"]').addClass('hidden');
        $('td.form-batch-control[data-name="' + field + '"]').addClass('hidden');
        $($('template.form-batch-template')[0].content).find('td.form-batch-control[data-name="' + field + '"]').addClass('hidden');
    });
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
