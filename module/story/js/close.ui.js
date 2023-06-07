function setStory(event)
{
    var $form         = $(event.target).closest('form');
    var $closedReason = $form.find('#closedReason').val();
    var $duplicateBox = $form.find('#duplicateStoryBox');

    if($closedReason == 'duplicate')
    {
        $duplicateBox.show();
    }
    else
    {
        $duplicateBox.hide();
    }
}
