$(document).ready(function()
{
    $('#projectSearch').click(function()
    {
        triggerSearch();
    });
    $('#keyword').keypress(function(event)
    {
        if(event.which == 13) triggerSearch();
    })

});

function triggerSearch()
{
    var keyword = $('#keyword').val();
    vars = vars.replace('%s', keyword);
    var link = createLink('gitlab', 'browseProject', 'gitlabID=' + gitlabID + '&' + vars);
    window.location.href = link;
}
