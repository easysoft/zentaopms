window.showRecalculateProgress = function(code = 'all')
{
    var calcType = $('.isCalcAll').prop('checked') ? 'all' : 'inference';
    var link = $.createLink('metric', 'recalculateProgress', 'calcType=' + calcType + '&code=' + code);
    loadPage(link);
}
