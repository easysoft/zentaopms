function setStory(event)
{
    var $form         = $(event.target).closest('form');
    var closedReason  = $form.find('#closedReason').find('.pick-value').val();
    var $duplicateBox = $form.find('#duplicateStoryBox');

    $duplicateBox.toggleClass('hidden', closedReason != 'duplicate');
}

$('[type="submit"]').on('click', function()
{
    if(closeParent)
    {
        zui.Modal.confirm(closeParentNotice).then(result =>
        {
            if(result)
            {
                const url = $.createLink('story', 'close', 'storyID=' + storyID);
                let formData = {};
                formData['closedReason']   = $('[name=closedReason]').val();
                formData['duplicateStory'] = $('[name=duplicateStory]').val();
                formData['comment']        = $('[name=comment]').val();
                formData['uid']            = $('[name=uid]').val();

                $.ajaxSubmit({url: url, data: formData});
            }
        });

        return false;
    }
})
