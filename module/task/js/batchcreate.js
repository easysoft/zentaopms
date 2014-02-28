$(function() {
    for(i=0; i<batchCreateNum; i++) $("#story" + i).chosen({no_results_text: noResultsMatch});
})

/* Copy story title as task title. */
function copyStoryTitle(num)
{
    var storyTitle = $('#story' + num).find('option:selected').text();
    startPosition  = storyTitle.indexOf(':') + 1;
    endPosition    = storyTitle.lastIndexOf('(');
    storyTitle     = storyTitle.substr(startPosition, endPosition - startPosition);
    $('#story' + num).parent().next().find('input:first').val(storyTitle);
}
