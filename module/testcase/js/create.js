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
        $('#preview').removeClass('hidden');
        $('#preview').attr('href', storyLink);
    }
}
$(function()
{
     $("#story").chosen({no_results_text: noResultsMatch});
     $("#preview").colorbox({width:1000, height:600, iframe:true, transition:'elastic', speed:350, scrolling:true});
})
