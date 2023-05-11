/**
 * 待办转研发需求。
 * Change todo to story.
 *
 * @return void
 */
function toStory()
{
    loadPage($.createLink('story', 'create', 'productID=' + $('#product').val() + '&branch=0&moduleID=0&storyID=0&projectID=0&bugID=0&planID=0&todoID=' + todoID, config.defaultView));
}

/**
 * 待办转任务。
 * Change todo to task.
 *
 * @return void
 */
function toTask()
{
    var executionID = $('#execution').val();
    if(!executionID)
    {
        alert(selectExecution);
        return;
    }

    loadPage($.createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=' + todoID, config.defaultView));
}

/**
 * 待办转bug。
 * Change todo to bug.
 *
 * @return void
 */
function toBug()
{
    var productID = $('#bugProduct').val();
    if(!productID)
    {
        alert(selectProduct);
        return;
    }

    loadPage($.createLink('bug', 'create', 'productID=' + productID + '&branch=0&extras=todoID=' + todoID, config.defaultView));
}

/**
 * 跳转至创建项目页面。
 * Link to create project.
 *
 * 开源版没有调用此方法。
 *
 * @return void
 */
function createProject()
{
    loadPage($.createLink('project', 'create'));
}

/**
 * 跳转至创建执行页面。
 * Create execution.
 *
 * 开源版没有调用此方法。
 *
 * @return void
 */
function createExecution()
{
    loadPage($.createLink('execution', 'create'));
}

/**
 * 通过 projectID 获取执行。
 * Get executions by project id.
 *
 * @param  int  projectID
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
 * 获取产品。
 * Get products by projectID.
 *
 * @param  int  projectID
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
 * 获取产品项目集。
 * Get programs by productID.
 *
 * @param  int  productID
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
 * 展开收起执行下拉菜单。
 * Toggle show execution.
 *
 * 开源版没有调用此方法。
 *
 * @param  bool multiple
 * @return void
 */
function toggleExecution(multiple)
{
    $('#executionIdBox').closest('tr').toggleClass('hidden', !multiple);
}
