window.showRecalculateProgress = function()
{
    var calcType = $('.isCalcAll').prop('checked') ? 'all' : 'inference';
    var link = $.createLink('metric', 'recalculateProgress', 'calcType=' + calcType);
    loadPage(link);
}
