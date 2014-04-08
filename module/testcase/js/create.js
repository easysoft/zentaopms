/* Set the story priview link. */
function setPreview()
{
    if(!$('#story').val())
    {
        $('#preview').addClass('hidden');
    }
    else
    {
        storyLink = createLink('story', 'view', "storyID=" + $('#story').val());
        var concat = config.requestType == 'PATH_INFO' ? '?'  : '&';
        storyLink  = storyLink + concat + 'onlybody=yes';
        $('#preview').removeClass('hidden');
        $('#preview').attr('href', storyLink);
    }
}

$(function()
{
     $("#story").chosen({no_results_text: noResultsMatch});
     $("#preview").modalTrigger({width:960, type:'iframe'});
})
