window.setStory = function(event)
{
    var $form         = $(event.target).closest('form');
    var closedReason  = $form.find('#closedReason').find('.pick-value').val();
    var $duplicateBox = $form.find('#duplicateStoryBox');

    $duplicateBox.closest('.form-row').toggleClass('hidden', closedReason != 'duplicate');
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

$(function()
{
    $('#duplicateStoryBox').closest('.form-row').addClass('hidden');
});
