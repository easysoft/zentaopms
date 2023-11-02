/**
 * 待办转研发需求。
 * Change todo to story.
 *
 * @return void
 */
function toStory()
{
    const objectID = vision != 'lite' ? $('#productModal input[name=product]').val() : $('#projectModal input[name=projectToStory]').val();
    const link     = vision != 'lite' ? $.createLink('story', 'create', 'productID=' + objectID + '&branch=0&moduleID=0&storyID=0&projectID=0&bugID=0&planID=0&todoID=' + todoID, config.defaultView) : $.createLink('story', 'create', 'productID=0&branch=0&moduleID=0&storyID=0&projectID=' + objectID + '&bugID=0&planID=0&todoID=' + todoID, config.defaultView);

    zui.Modal.hide('.m-my-todo');
    openPage(link,  vision != 'lite' ? 'product' : 'project');
}

/**
 * 待办转任务。
 * Change todo to task.
 *
 * @return void
 */
function toTask()
{
    const executionID = $('#executionModal input[name=execution]').val();
    if(!executionID)
    {
        zui.Modal.alert(selectExecution);
        return false;
    }

    zui.Modal.hide('.m-my-todo');
    openPage($.createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=' + todoID), vision == 'lite' ? 'project' : 'execution');
}

/**
 * 待办转bug。
 * Change todo to bug.
 *
 * @return void
 */
function toBug()
{
    const productID = $('#projectProductModal input[name=bugProduct]').val();
    if(!productID)
    {
        zui.Modal.alert(selectProduct);
        return false;
    }

    zui.Modal.hide('.m-my-todo');
    openPage($.createLink('bug', 'create', 'productID=' + productID + '&branch=0&extras=todoID=' + todoID), 'qa');
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
function getExecutionByProject(e)
{
    const projectID = $(e.target).val();
    const link      = $.createLink('todo', 'ajaxGetExecutionPairs', "projectID=" + projectID);
    $.getJSON(link, function(data)
    {
        const executionID      = data.length ? data[0].value : 0;
        const $executionPicker = $('#executionModal input[name=execution]').zui('picker');
        $executionPicker.render({items: data});
        $executionPicker.$.setValue(executionID);
    })
}

/**
 * 获取产品。
 * Get products by projectID.
 *
 * @param  int  projectID
 * @return void
 */
function getProductByProject(e)
{
    const projectID = $(e.target).val();
    var link = $.createLink('todo', 'ajaxGetProductPairs', "projectID=" + projectID);
    $.getJSON(link, function(data)
    {
        const productID      = data.length ? data[0].value : 0;
        const $productPicker = $('#projectProductModal input[name=bugProduct]').zui('picker');
        $productPicker.render({items: data});
        $productPicker.$.setValue(productID);
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

function createProduct()
{
    loadModal($.createLink('product', 'create'), '.m-my-todo');
}
