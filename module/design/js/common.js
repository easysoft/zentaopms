$(document).on('click', '.ajaxPager', function()
{   
    $('#logBox').load($(this).attr('href'));
    return false;
})
$('#product').change(function()
{
    productID = $(this).val();
    var link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=all&moduleID=0&storyID=0&onlyOption=false&status=active');
    $.post(link, function(data)
    {
        $('#story').replaceWith(data);
        $('#story_chosen').remove();
        $('#story').next('.picker').remove();
        $('#story').chosen();
    })
})
