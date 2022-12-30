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
    }
    else if(resolution == 'subdivided')
    {
        $('#duplicateStoryBox' + storyID).hide();
        $('#childStoryBox' + storyID).show();
    }
    else
    {
        $('#duplicateStoryBox' + storyID).hide();
        $('#childStoryBox' + storyID).hide();
    }
}

$(function()
{
    $('td[id^="duplicateStoryBox"]').on('mouseenter', 'select[id^="duplicateStoryIDList"]', function()
    {
        var options = $(this).find('option').length;
        if(options <= 1)
        {
            var id = $(this).attr('id');
            var storyID = id.replace('duplicateStoryIDList', '');
            var link = createLink('story', 'ajaxGetStoryPairs', 'storyID=' + storyID);
            var that = $(this);

            $.get(link, function(data)
            {
                that.replaceWith(data);
                $("#duplicateStoryIDList" + storyID).picker(
                {
                    disableEmptySearch : true,
                    dropWidth : 'auto',
                    onReady: function(event)
                    {
                        $(event.picker.$container).addClass('required');
                    }

                });
            })
        }
    })
});
