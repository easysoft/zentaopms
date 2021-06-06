$('.new-estimate input').keyup(function()
{
    computeAverage();
})

$(function()
{
    $('#round_chosen').click(function()
    {
        var maxHeight = $(window).height() - 150;
        $('.chosen-container .chosen-results').attr('style', 'max-height: ' + maxHeight + 'px !important;');
    })
})

function computeAverage()
{
    var summary = 0;
    var count   = 0;
    var average = 0;
    $('.new-estimate').each(function()
    {
        var value = $(this).find('input').val();
        if(value)
        {
            value = parseFloat(value);
            if(isNaN(value)) value = 0;
            summary += value;
            count   += 1;
        }
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
    $('.empty-th').addClass('hide');
}

function selectRound(round)
{
    location.href = createLink('execution', 'storyEstimate', 'executionID=' + executionID + '&storyID=' + storyID + '&round=' + round);
}
