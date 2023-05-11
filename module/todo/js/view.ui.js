/**
 * 待办转研发需求。
 * todo change to story.
 *
 * @access public
 * @return void
 */
function toStory()
{
    loadPage($.createLink('story', 'create', 'productID=' + $('#product').val() + '&branch=0&moduleID=0&storyID=0&projectID=0&bugID=0&planID=0&todoID=' + todoID, config.defaultView));
}

/**
 * 待办转任务。
 * todo change to task.
 *
 * @access public
 * @return void
 */
function toTask()
{
    var executionID = $('#execution').val();
    if(!executionID)
    {
        alert(selectExecution);
        return false;
    }

    loadPage($.createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=' + todoID, config.defaultView));
}

/**
 * 待办转bug。
 * todo change to bug.
 *
 * @access public
 * @return void
 */
function toBug()
{
    var productID = $('#bugProduct').val();
    if(!productID)
    {
        alert(selectProduct);
        return false;
    }

    loadPage($.createLink('bug', 'create', 'productID=' + productID + '&branch=0&extras=todoID=' + todoID, config.defaultView));
}

/**
 * 开源版没有调用此方法。
 * 跳转至创建项目页面。
 * Link to create project.
 *
 * @access public
 * @return void
 */
function createProject()
{
    loadPage($.createLink('project', 'create'));
}

/**
 * 开源版没有调用此方法。
 * 跳转至创建执行页面。
 * Create execution.
 *
 * @access public
 * @return void
 */
function createExecution()
{
    loadPage(createLink('execution', 'create'));
}

/**
 * 通过 projectID 获取执行。
 * Get executions by project id.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function getExecutionByProject(projectID)
{

    var link = $.createLink('todo', 'ajaxGetExecutionPairs', "projectID=" + projectID);

    $('#executionIdBox').load(link, function()
    {
        $(this).find('select').chosen();
    })
}

/**
 * 通过 projectID 获取产品。
 * Get products by project id.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function getProductByProject(projectID)
{
    var link = $.createLink('todo', 'ajaxGetProductPairs', "projectID=" + projectID);
    $('#productIdBox').load(link, function()
    {
        $(this).find('select').chosen();
    })
}

/**
 * 通过 productID 获取项目集。
 * Get programs by product id.
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
function getProgramByProduct(productID)
{
    var link = $.createLink('todo', 'ajaxGetProgramID', "productID=" + productID + '&type=product');
    $.post(link, function(data)
    {
        $('#productProgram').val(data);
    })
}

/**
 * 开源版没有调用此方法。
 * 展开收起执行下拉菜单。
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
