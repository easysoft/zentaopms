/**
 * Set duplicate field.
 *
 * @param  string $resolution
 * @param  int    $storyID
 * @access public
 * @return void
 */
function setDuplicateAndChild(resolution, storyID)
{
    if(resolution == 'duplicate')
    {
        $('#childStoryBox' + storyID).hide();
        $('#duplicateStoryBox' + storyID).show();
        $('#duplicateStoryTitle').show();
        $('.form-actions').attr('colspan', 6);
    }
    else if(resolution == 'subdivided')
    {
        $('#duplicateStoryBox' + storyID).hide();
        $('#childStoryBox' + storyID).show();
        $('#duplicateStoryTitle').hide();
        $('.form-actions').attr('colspan', 5);
    }
    else
    {
        $('#duplicateStoryBox' + storyID).hide();
        $('#childStoryBox' + storyID).hide();
        $('#duplicateStoryTitle').hide();
        $('.form-actions').attr('colspan', 5);
    }
}

$(function()
{
    $('select[id^="duplicateStoryIDList"]').picker(
    {
        disableEmptySearch : true,
        dropWidth : 'auto',
        onReady: function(event)
        {
            $(event.picker.$container).addClass('required');
        }

    });
});
