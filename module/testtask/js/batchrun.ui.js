function toggleAction()
{
    const result       = $(this).val();
    const stepsOrReals = $(this).parents('tr').find('table .steps, table .reals');
    if(result == 'pass')
    {
        stepsOrReals.addClass('hidden');

        stepsOrReals.find('select[id^=steps]').val(result);
        if(stepsOrReals.parent().prop('tagName') == 'TR')
        {
            stepsOrReals.closest('tbody').children('tr').each(function()
            {
                var $td = $(this).children('td').first();
                if($td.attr('colspan') != undefined) $td.attr('colspan', 2);
            });
        }
    }
    else
    {
        stepsOrReals.removeClass('hidden');

        stepsOrReals.find('select[id^=steps]').eq(-1).val(result);

        if(stepsOrReals.parent().prop('tagName') == 'TR')
        {
            stepsOrReals.closest('tbody').children('tr').each(function()
            {
                var $td = $(this).children('td').first();
                if($td.attr('colspan') != undefined) $td.attr('colspan', 4);
            });
        }
    }
}

$(function()
{
    /* Readjust precondition width by cases precondition. */
    preconditionThWidth  = $('th.precondition').width();
    $preconditionTD      = $('tbody td.precondition');
    preconditionTdLength = $preconditionTD.length;

    for(i = 0; i < preconditionTdLength; i++)
    {
        preconditionTextWidth = $preconditionTD.eq(i).find('span').first().width();
        if(preconditionTextWidth > preconditionThWidth)
        {
            preconditionThWidth = preconditionTextWidth;
            if(preconditionThWidth > 200) preconditionThWidth = 200;

            $('th.precondition').width(preconditionThWidth);
        }
    }
})

function toggleStep(event)
{
    var $target    = $(event.target);
    var $preSelect = $(event.target).closest('tr').find('[name^="steps"]');

    if($target.val() == '' && $preSelect.val() == 'fail')
    {
        $preSelect.zui('picker').$.changeState({value: 'pass'});
    }
    else if($target.val() != '' && $preSelect.val() == 'pass')
    {
        $preSelect.zui('picker').$.changeState({value: 'fail'});
    }
}
