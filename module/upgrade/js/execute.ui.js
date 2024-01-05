window.submitConfirm = function(event) {
    $(event.target).addClass('disabled');
    zui.Modal.open({id: 'progress'});

    updateProgressInterval();
    updateProgress();
}
