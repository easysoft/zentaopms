function setStory(event)
{
    var $form         = $(event.target).closest('form');
    var closedReason  = $form.find('#closedReason').find('.pick-value').val();
    var $duplicateBox = $form.find('#duplicateStoryBox');

    $duplicateBox.toggleClass('hidden', closedReason != 'duplicate');
}

async function checkUndoneTasks()
{
    if(undoneTasks == 0) return true;

     const confirmed = await zui.Modal.confirm(confirmCloseTips);
    return confirmed;
}

window.checkSubmit = function()
{
    return checkUndoneTasks();
};
