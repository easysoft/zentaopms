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
    link = createLink('story', 'ajaxUnlinkStory', 'storyID=' + storyID + '&type=' + linkType + '&story2Unlink=' + story2Unlink);
    if(linkType == 'linkStories')  $('#linkStoriesBox').load(link);
    if(linkType == 'childStories') $('#childStoriesBox').load(link);
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
