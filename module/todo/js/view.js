$(function()
{
    $('#toStoryLink').click(function()
    {
        $('#productModal .modal-body .input-group .input-group-btn').addClass('hidden');
        $('#productModal #toStoryButton').closest('.input-group-btn').removeClass('hidden');
    })

    $('#toBugLink').click(function()
    {
        $('#productModal .modal-body .input-group .input-group-btn').addClass('hidden');
        $('#productModal #toBugButton').closest('.input-group-btn').removeClass('hidden');
    })

    $('#toTaskButton').click(function()
    {
        var onlybody    = config.onlybody;
        config.onlybody = 'no';

        var projectID = $(this).closest('.input-group').find('#project').val();
        var link      = createLink('task', 'create', 'projectID=' + projectID + '&storyID=0&moduleID=0&taskID=0&todoID=' + todoID);

        config.onlybody      = onlybody;
        parent.location.href = link;
    })

    $('#toStoryButton').click(function()
    {
        var onlybody    = config.onlybody;
        config.onlybody = 'no';

        var productID = $(this).closest('.input-group').find('#product').val();
        var link      = createLink('story', 'create', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&projectID=0&bugID=0&planID=0&todoID=' + todoID);

        config.onlybody      = onlybody;
        parent.location.href = link;
    })

    $('#toBugButton').click(function()
    {
        var onlybody    = config.onlybody;
        config.onlybody = 'no';

        var productID = $(this).closest('.input-group').find('#product').val();
        var link      = createLink('bug', 'create', 'productID=' + productID + '&branch=0&extras=todoID=' + todoID);

        config.onlybody      = onlybody;
        parent.location.href = link;
    })
});
