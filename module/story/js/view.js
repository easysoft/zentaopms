$(function() 
{
    if(canCreate)
    {
        link = createLink('story', 'create', 'productID=' + productID + '&moduleID=' + moduleID);
        $('#modulemenu ul.nav li.right:first').before("<li class='right'><a href='" + link + "'><i class='icon-story-create icon-plus'></i> " + createStory + "</a></li>");
    }
});
