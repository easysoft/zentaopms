window.updateAverage = function()
{
    var $estimates = $('.story-estimate');
    if($('.new-estimate').length > 0) $estimates = $('.new-estimate');

    var summary = 0;
    var count   = 0;
    var average = 0;
    $estimates.each(function()
    {
        var value = $(this).find('input[name^="estimate"]').val();
        if(value)
        {
            value = parseFloat(value);
            if(isNaN(value)) value = 0;
            if(value != 0)
            {
                summary += value;
                count   += 1;
            }
        }
    })

    if(count) average = summary / count;
    $('#average').val(average.toFixed(2));
}

window.selectRound = function(loadUrl)
{
    loadModal(loadUrl.replace('%s', $('input[name="round"]').val()), 'current');
}
