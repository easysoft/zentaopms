window.submitConfirm = function(event) {
    $(this).addClass('disabled');
    zui.Modal.open({id: 'progress'});

    updateProgressInterval();
    updateProgress();
}
