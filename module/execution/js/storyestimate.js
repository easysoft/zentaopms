$('.new-estimate input').keyup(function()
{
    computeAverage();
})

function computeAverage()
{
    var summary = 0;
    var count   = 0;
    var average = 0;
    $('.new-estimate').each(function()
    {
        var value = $(this).find('input').val();
        if(!value) return false;
        value = parseFloat(value);
        if(isNaN(value)) value = 0;
        summary += value;
        count   += 1;
    })

    if(count) average = summary / count;
    $('#average').val(average.toFixed(2));
    $('#showAverage').html(average.toFixed(2));
}

function showNewEstimate()
{
    $('.th-new-estimate').removeClass('hide');
    $('.new-estimate').removeClass('hide');
    $('.form-actions').removeClass('hide');
}

function selectRound(round)
{
    location.href = createLink('execution', 'storyEstimate', 'executionID=' + executionID + '&storyID=' + storyID + '&round=' + round);
}
