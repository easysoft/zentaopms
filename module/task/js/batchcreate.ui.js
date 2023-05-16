/* Get select of stories.*/
function setStories()
{
    const moduleID = $(this).val();
    const index    = $(this).closest('td').attr('data-init');
    const link     = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=0&branch=all&moduleID=' + moduleID + '&storyID=0&num=' + index + '&type=short');
    $.get(link, function(stories)
    {
        var $story  = $('#story_' + index);
        var storyID = $story.val();
        if(!stories) stories = '<select id="story' + index + '" name="story[' + index + ']" class="form-control"></select>';
        $story.replaceWith(stories);
        $story.val(storyID);
        if($('#zeroTaskStory').hasClass('checked'))
        {
            $story.find('option').each(function()
            {
                value = $(this).attr('value');
                if(value != 'ditto' && storyTasks[value] > 0)
                {
                    $(this).hide();
                    if(storyID == value) $story.val('');
                }
            })
        }
    });
}
