$(function()
{
    if(canCreate)
    {
         var createStoryLink = createLink('story', 'create', 'productID=' + productID + "&branch=" + branch + '&moduleID=' + moduleID);
        $('#modulemenu ul.nav li.right:first').before("<li class='right'><a href='" + createStoryLink + "'><i class='icon-story-create icon-plus'></i> " + createStory + "</a></li>");
    }

    /* Add for task #5385. */
    if(config.onlybody == 'yes') $('.main-actions').css('width', '100%')

    $('.legendStories').mouseover(function()
    {
        $(this).parent('ul').find('a.removeButton').addClass('hide');
        $(this).find('.removeButton').removeClass('hide');
    });

    $('.legendStories').mouseout(function()
    {
        $(this).find('.removeButton').addClass('hide');
    });

    $('#legendStories').find('.removeButton').click(function()
    {
        $(this).closest('li').remove();
    })
});
