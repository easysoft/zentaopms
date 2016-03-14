/**
 * Unlink story.
 *
 * @param  int    $storyID
 * @param  string $linkType 
 * @param  int    story2Unlink
 * @access public
 * @return void
 */
function unlinkStory(storyID, linkType, story2Unlink)
{
    link = createLink('story', 'unlinkStory', 'storyID=' + storyID + '&type=' + linkType + '&story2Unlink=' + story2Unlink);

    $.get(link, function(data)
    {
        if(data == 'success')
        {
            if(linkType == 'linkStories')  $('#linkStoriesBox').load(createLink('story', 'ajaxGetLinkedStories', 'storyID=' + storyID + '&type=' + linkType));
            if(linkType == 'childStories') $('#childStoriesBox').load(createLink('story', 'ajaxGetLinkedStories', 'storyID=' + storyID + '&type=' + linkType));
        }
    });
}

/**
 * Load linked stories. 
 * 
 * @param  int    $storyID 
 * @param  string $linkType 
 * @access public
 * @return void
 */
function loadLinkedStories(storyID, linkType)
{
    storyLink = createLink('story', 'ajaxGetLinkedStories', 'storyID=' + storyID + '&type=' + linkType);
    if(linkType == 'linkStories')  $('#linkStoriesBox').load(storyLink);
    if(linkType == 'childStories') $('#childStoriesBox').load(storyLink);
}
