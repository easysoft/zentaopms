function setStory(event)
{
    var $form         = $(event.target).closest('form');
    var closedReason  = $form.find('#closedReason').find('.pick-value').val();
    var $duplicateBox = $form.find('#duplicateStoryBox');

    $duplicateBox.toggleClass('hidden', closedReason != 'duplicate');
}
