window.submitConfirm = function(event) {

    zui.Modal.open({id: 'progress'});
    $(event.target).addClass('disabled');
    $(event.target).css('pointer-events', 'none');
    $('#upgradingTips').removeClass('hidden');
    if(writable)
    {
        updateProgressInterval();
        updateProgress();
    }
}
