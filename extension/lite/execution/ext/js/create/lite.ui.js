window.dateRangeChange = function()
{
    const begin = $('input[name=begin]').val();
    const end   = $('input[name=end]').val();
    $('[name=days]').val(computeDaysDelta(begin, end));

    const beginDate = new Date($('[name=begin]').zui('datePicker').$.value);
    const endDate   = new Date($('[name=end]').zui('datePicker').$.value);
    const days      = parseInt((endDate.getTime() - beginDate.getTime()) / (24 * 60 * 60 * 1000)) + 1;
    if(parseInt($('input[name=delta]:checked').val()) != 999) $('[name=delta]').prop('checked', false);
    if($('#delta' + days).length > 0 && days != 999) $('#delta' + days).prop('checked', true);
}
