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
        var executionID = $(this).closest('.input-group').find('#execution').val();
        if(!executionID)
        {
            alert(selectExecution);
            return false;
        }
        var onlybody    = config.onlybody;
        var projectID   = $('#project').val();
        var link        = createLink('task', 'create', 'projectID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=' + todoID, config.defaultView, 'no', projectID);

        parent.location.href = link;
    })

    $('#toStoryButton').click(function()
    {
        var onlybody    = config.onlybody;
        var programID   = $('#productProgram').val();

        var productID = $(this).closest('.input-group').find('#product').val();
        var link      = createLink('story', 'create', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&projectID=0&bugID=0&planID=0&todoID=' + todoID, config.defaultView, 'no', programID);

        parent.location.href = link;
    })

    $('#toBugButton').click(function()
    {
        var onlybody    = config.onlybody;
        var programID   = $('#project').val();

        var productID = $(this).closest('.input-group').find('#product').val();
        var link      = createLink('bug', 'create', 'productID=' + productID + '&branch=0&extras=todoID=' + todoID, config.defaultView, 'no', programID);

        parent.location.href = link;
    })

    $('#project, #product').change();
});

function createProduct()
{
    var onlybody    = config.onlybody;
    config.onlybody = 'no';

    var link = createLink('product', 'create');

    config.onlybody      = onlybody;
    parent.location.href = link;
}

function createProject()
{
    var onlybody    = config.onlybody;
    config.onlybody = 'no';

    var link      = createLink('project', 'create');

    config.onlybody      = onlybody;
    parent.location.href = link;
}

function getExecutionByProject(projectID)
{
    link = createLink('todo', 'ajaxGetExecutionPairs', "projectID=" + projectID);
    $('#executionIdBox').load(link, function(){
        $(this).find('select').chosen();
    })
}

function getProductByProject(projectID)
{
    link = createLink('todo', 'ajaxGetProductPairs', "projectID=" + projectID);
    $('#productIdBox').load(link, function(){
        $(this).find('select').chosen();
    })
}

function getProgramByProduct(productID)
{
    link = createLink('todo', 'ajaxGetProgramID', "productID=" + productID + '&type=product');
    $.post(link, function(data)
    {
        $('#productProgram').val(data);
    })
}
