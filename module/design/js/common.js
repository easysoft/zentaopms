$(document).on('click', '.ajaxPager', function()
{   
    $('#logBox').load($(this).attr('href'));
    return false;
})
$('#product').change(function()
{
    productID = $(this).val();
    var link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID);
    $.post(link, function(data)
    {
        $('#story').replaceWith(data);
        $('#story_chosen').remove();
        $('#story').chosen();
    })
})
