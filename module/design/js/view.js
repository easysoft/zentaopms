$(function()
{
    $('#subNavbar .nav li[data-id=' + type.toLowerCase() + ']').addClass('active');
    $('#linkCommit').click(function()
    {
        if(repos.length == 0)
        {
            var onlybody    = config.onlybody;
            config.onlybody = 'no';

            var link = createLink('repo', 'create', 'objectID=' + projectID);

            config.onlybody = onlybody;
            window.parent.$.apps.open(link, 'project');

            return false;
        }
    });
})
