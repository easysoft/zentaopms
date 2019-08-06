$(function()
{
    $('#needNotReview').on('change', function()
    {
        $('#assignedTo').attr('disabled', $(this).is(':checked') ? 'disabled' : null).trigger('chosen:updated');
        getStatus('change', "storyID=" + storyID + ",changed=" + changed + ",needNotReview=" + ($(this).prop('checked') ? 1 : 0));
    });
    $('#needNotReview').change();

    $specBox   = $('#spec').closest('td').find('.ke-container iframe.ke-edit-iframe').contents().find('.article-content');
    $verifyBox = $('#verify').closest('td').find('.ke-container iframe.ke-edit-iframe').contents().find('.article-content');
    $('#title').change(function()
    {
        newChanged = ($(this).val() != oldStoryTitle || $specBox.html() != oldStorySpec || $verifyBox.html() != oldStoryVerify || $('.file-input-list .file-input.normal').length > 0) ? 1 : 0;
        if(changed != newChanged)
        {
            changed = newChanged;
            getStatus('change', "storyID=" + storyID + ",changed=" + changed + ",needNotReview=" + ($('#needNotReview').prop('checked') ? 1 : 0));
        }
    });
    $('.ke-container iframe.ke-edit-iframe').contents().find('.article-content').keyup(function()
    {
        newChanged = ($('#title').val() != oldStoryTitle || $specBox.html() != oldStorySpec || $verifyBox.html() != oldStoryVerify || $('.file-input-list .file-input.normal').length > 0) ? 1 : 0;
        if(changed != newChanged)
        {
            changed = newChanged;
            getStatus('change', "storyID=" + storyID + ",changed=" + changed + ",needNotReview=" + ($('#needNotReview').prop('checked') ? 1 : 0));
        }
    });

    if($('.tabs .tab-content .tab-pane.active').children().length == 0) $('.tabs .nav-tabs li.active').css('border-bottom', '1px solid #ccc');
});
