window.showRecalculateProgress = function(calcRange = 'all', code = '')
{
    var modal = $('.modal-dialog').closest('.modal');
    var modalID = modal.attr('id');
    var calcType = $('.isCalcAll').prop('checked') ? 'all' : 'inference';
    var link = $.createLink('metric', 'recalculate', 'calcType=' + calcType + '&calcRange=' + calcRange + '&code=' + code);
    openUrl(link, {load: 'modal', target: modalID});
}
