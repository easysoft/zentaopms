window.showRecalculateProgress = function(calcRange = 'all', code = '')
{
    var calcType = $('.isCalcAll').prop('checked') ? 'all' : 'inference';
    var link = $.createLink('metric', 'recalculate', 'calcType=' + calcType + '&calcRange=' + calcRange + '&code=' + code);
    loadPage(link);
}
