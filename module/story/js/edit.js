/**
 * Delete linked story. 
 *
 * @param  int    $storyID
 * @param  string $linkType 
 * @param  int    deleteStory
 * @access public
 * @return void
 */
function deleteLinkedStory(storyID, linkType, deleteStory)
{
    deleteLink = createLink('story', 'ajaxDeleteLinkedStory', 'storyID=' + storyID + '&type=' + linkType + '&deleteStory=' + deleteStory);
    if(linkType == 'linkStories') $('#linkStoriesBox').load(deleteLink);
    if(linkType == 'childStories') $('#childStoriesBox').load(deleteLink);
}

/**
 * Load linked stories. 
 * 
 * @param  int    $storyID 
 * @param  string $linkType 
 * @param  string $linkedStories 
 * @access public
 * @return void
 */
function loadLinkedStories(storyID, linkType, linkedStories)
{
    storyLink = createLink('story', 'ajaxGetLinkedStories', 'storyID=' + storyID + '&type=' + linkType + '&linkedStories=' + linkedStories);
    if(linkType == 'linkStories') $('#linkStoriesBox').load(storyLink);
    if(linkType == 'childStories') $('#childStoriesBox').load(storyLink);
}
