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
     $("#preview").colorbox({width:960, height:550, iframe:true, transition:'none', scrolling:true});
})
