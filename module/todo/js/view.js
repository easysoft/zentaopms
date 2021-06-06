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
        var executionID = $('#execution').val();
        if(!executionID)
        {
            alert(selectExecution);
            return false;
        }
        var onlybody  = config.onlybody;
        var projectID = $('#project').val();
        var link      = createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=' + todoID, config.defaultView, 'no', projectID);

        location.href = link;
    })

    $('#toStoryButton').click(function()
    {
        var onlybody  = config.onlybody;
        var programID = $('#productProgram').val();
        var productID = $('#product').val();
        var link      = createLink('story', 'create', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&projectID=0&bugID=0&planID=0&todoID=' + todoID, config.defaultView, 'no', programID);

        location.href = link;
    })

    $('#toBugButton').click(function()
    {
        var onlybody  = config.onlybody;
        var projectID = $('#bugProject').val();
        var productID = $('#bugProduct').val();
        var link      = createLink('bug', 'create', 'productID=' + productID + '&branch=0&extras=todoID=' + todoID, config.defaultView, 'no', projectID);

        location.href = link;
    })

    $('#project, #product').change();
});

/**
 * Link to create product.
 *
 * @access public
 * @return void
 */
function createProduct()
{
    var onlybody    = config.onlybody;
    config.onlybody = 'no';

    var link = createLink('product', 'create');

    config.onlybody      = onlybody;
    parent.location.href = link;
}

/**
 * Link to create project.
 *
 * @access public
 * @return void
 */
function createProject()
{
    var onlybody    = config.onlybody;
    config.onlybody = 'no';

    var link      = createLink('project', 'create');

    config.onlybody      = onlybody;
    parent.location.href = link;
}

/**
 * Get executions by project id.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function getExecutionByProject(projectID)
{
    link = createLink('todo', 'ajaxGetExecutionPairs', "projectID=" + projectID);
    $('#executionIdBox').load(link, function()
    {
        $(this).find('select').chosen();
    })
}

/**
 * Get products by project id.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function getProductByProject(projectID)
{
    link = createLink('todo', 'ajaxGetProductPairs', "projectID=" + projectID);
    $('#productIdBox').load(link, function()
    {
        $(this).find('select').chosen();
    })
}

/**
 * Get programs by product id.
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function getProgramByProduct(productID)
{
    link = createLink('todo', 'ajaxGetProgramID', "productID=" + productID + '&type=product');
    $.post(link, function(data)
    {
        $('#productProgram').val(data);
    })
}
