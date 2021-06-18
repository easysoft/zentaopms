$(function()
{
    $('.main-side #branch').closest('td').find('#product_chosen .chosen-single').css('width', '153px');
    $('#reviewer_chosen .search-choice').each(function()
    {
        if(reviewedReviewer.indexOf($(this).find('span').text()) != -1) $(this).css('pointer-events', 'none');
    })
})
