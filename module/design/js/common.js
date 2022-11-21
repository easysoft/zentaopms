$(document).on('click', '.ajaxPager', function()
{
    $('#logBox').load($(this).attr('href'));
    return false;
})
$('#product').change(function()
{
    productID = $(this).val();
    var link = createLink('design', 'ajaxGetProductStories', 'productID=' + productID + '&projectID=' + projectID + '&status=active&hasParent=false');
    $.post(link, function(data)
    {
        $('#story').replaceWith(data);
        $('#story_chosen').remove();
        $('#story').next('.picker').remove();
        $('#story').chosen();
    })
})
