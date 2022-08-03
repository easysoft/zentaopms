$(function()
{
    $('.main-side #branch').closest('td').find('#product_chosen .chosen-single').css('width', '153px');
    $('#reviewer_chosen .search-choice').each(function()
    {
        if(reviewedReviewer.indexOf($(this).find('span').text()) != -1) $(this).css('pointer-events', 'none');
    })

    $('#reviewer').change(function()
    {
        if($('#reviewer_chosen .search-choice').length === 1)
        {
            alert(reviewerNotEmpty);
            $('#reviewer').val(reviewers);
            $('#reviewer').trigger('chosen:updated');
        }
        else
        {
            reviewers = $('#reviewer').val();
        }
    })

    $('#source').on('change', function()
    {
        var source = $(this).val();
        if($.inArray(source, feedbackSource) != -1)
        {
            $('.feedbackBox').removeClass('hidden');
        }
        else
        {
            $('.feedbackBox').addClass('hidden');
        }
    });

    $('#linkStoriesLink').click(function()
    {
        var storyIdList = '';
        $('#linkStoriesBox input').each(function()
        {
            storyIdList += $(this).val() + ',';
        });

        var link = '';
        if(storyType == 'story')
        {
            link = createLink('story', 'linkStories', 'storyID=' + storyID + '&browseType=&excludeStories=' + storyIdList, '', true);
        }
        else
        {
            link = createLink('story', 'linkRequirements', 'storyID=' + storyID + '&browseType=&excludeStories=' + storyIdList, '', true);
        }

        var modalTrigger = new $.zui.ModalTrigger({type: 'iframe', width: '95%', url: link});
        modalTrigger.show();
    });
})
