$(function()
{
    if(config.onlybody == 'yes') $('.main-actions').css('width', '100%');

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
        var onlybody  = config.onlybody == 'yes';
        var link      = createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=' + todoID, config.defaultView, onlybody);

        if(!onlybody) window.parent.$.apps.open(link, 'execution');
        if(onlybody) location.href = link;
    })

    $('#toStoryButton').click(function()
    {
        var onlybody  = config.onlybody == 'yes';
        var productID = $('#product').val();
        var storyType = currentVision == 'or' ? 'requirement' : 'story';
        var link      = createLink('story', 'create', 'productID=' + productID + '&branch=0&moduleID=0&storyID=0&projectID=0&bugID=0&planID=0&todoID=' + todoID + '&extra=&storyType=' + storyType, config.defaultView, onlybody);

        if(!onlybody) window.parent.$.apps.open(link, 'product');
        if(onlybody) location.href = link;
    })

    $('#toBugButton').click(function()
    {
        var onlybody  = config.onlybody == 'yes';
        var productID = $('#bugProduct').val();
        if(!productID)
        {
            alert(selectProduct);
            return false;
        }

        var link = createLink('bug', 'create', 'productID=' + productID + '&branch=0&extras=todoID=' + todoID, config.defaultView, onlybody);
        if(!onlybody) window.parent.$.apps.open(link, 'qa');
        if(onlybody) location.href = link;
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

    config.onlybody = onlybody;
    window.parent.$.apps.open(link, 'product');
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

    var link = createLink('project', 'create');

    config.onlybody = onlybody;
    window.parent.$.apps.open(link, 'project');
}

/**
 * Create execution.
 *
 * @access public
 * @return void
 */
function createExecution()
{
    var onlybody    = config.onlybody;
    config.onlybody = 'no';

    var link = createLink('execution', 'create');

    config.onlybody = onlybody;
    window.parent.$.apps.open(link, 'execution');
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

/**
 * Toggle show execution.
 *
 * @param  bool $multiple
 * @access public
 * @return void
 */
function toggleExecution(multiple)
{
    $('#executionIdBox').closest('tr').toggleClass('hidden', !multiple);
}
