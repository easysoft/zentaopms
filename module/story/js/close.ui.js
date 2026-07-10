window.setStory = function(event)
{
    var $form         = $(event.target).closest('form');
    var closedReason  = $form.find('#closedReason').find('.pick-value').val();
    var $duplicateBox = $form.find('#duplicateStoryBox');

    $duplicateBox.closest('.form-row').toggleClass('hidden', closedReason != 'duplicate');
}

$(function()
{
    $('#duplicateStoryBox').closest('.form-row').addClass('hidden');
});
