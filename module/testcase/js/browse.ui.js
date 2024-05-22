$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();

    checkedList.forEach((id) => {
        const data = dtable.$.getRowInfo(id).data;
        if(data.isScene)  form.append('sceneIdList[]', data.caseID);
        if(!data.isScene) form.append('caseIdList[]',  data.caseID);
    });

    if($(this).hasClass('ajax-btn'))
    {
        if($(this).hasClass('batch-delete-btn'))
        {
            zui.Modal.confirm(confirmBatchDeleteSceneCase).then((res) => {if(res) $.ajaxSubmit({url, data:form});});
        }
        else
        {
            $.ajaxSubmit({url, data:form});
        }
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

/**
 * 拖拽用例或者场景。
 * Drag case or scene.
 *
 * @param  from   被拿起的元素
 * @param  to     放下时的目标元素
 * @param  type   放在目标元素的上方还是下方
 * @access public
 * @return bool
 */
window.onSortEnd = function(from, to, type)
{
    if(!from || !to) return false;

    if(!from.data.isScene && !to.data.isScene)
    {
        if(from.data.parent != to.data.parent) changeScene(from.data.id, to.data.parent);
        changeOrder(from.data.caseID, from.data.sort, to.data.caseID, to.data.sort, type, 'case');
    }
    else if(from.data.isScene && to.data.isScene)
    {
        if(from.data.parent == to.data.parent)
        {
            $('#dragModal').attr('data-sourceID',   from.data.id);
            $('#dragModal').attr('data-targetID',   to.data.id);
            $('#dragModal').attr('data-sourceSort', from.data.sort);
            $('#dragModal').attr('data-targetSort', to.data.sort);
            $('#dragModal').attr('data-type',       type);

            zui.Modal.open({id: 'dragModal'});
        }
        else
        {
            changeScene(from.data.id, to.data.id);
        }
    }
    else if(!from.data.isScene && to.data.isScene)
    {
        changeScene(from.data.id, to.data.id);
    }
    return true;
}

function changeScene(caseID, sceneID)
{
    const url  = $.createLink('testcase', 'changeScene');
    const form = new FormData();
    form.append('sourceID', caseID);
    form.append('targetID', sceneID);
    $.ajaxSubmit({url, data:form});
}

function changeOrder(sourceID, sourceOrder, targetID, targetOrder, type, module)
{
    const url  = $.createLink('testcase', 'updateOrder');
    const form = new FormData();
    form.append('sourceID',    sourceID);
    form.append('sourceOrder', sourceOrder);
    form.append('targetID',    targetID);
    form.append('targetOrder', targetOrder);
    form.append('type', type);
    form.append('module', module);
    $.ajaxSubmit({url, data:form});
}

/**
 * 拖拽的用例或者场景是否允许放下。
 * Is it allowed to drop the dragged case or scene.
 *
 * @param  from   被拿起的元素
 * @param  to     放下时的目标元素
 * @access public
 * @return bool
 */
window.canSortTo = function(from, to)
{
    if(!from || !to) return false;
    if(!from.data.isScene && !to.data.isScene && from.data.parent != 0 && from.data.parent != to.data.parent) return false;
    if(from.data.isScene && !to.data.isScene) return false;
    return true;
}

/**
 * 切换显示所有用例和自动化用例。
 * Toggles between displaying all cases and automation cases.
 *
 * @param  event $event
 * @access public
 * @return void
 */
window.toggleOnlyAutoCase = function(event)
{
    const onlyAutoCase = $(event.target).prop('checked') ? 1 : 0;
    $.cookie.set('onlyAutoCase', onlyAutoCase, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
}

/**
 * 标题列显示额外的内容。
 * Display extra content in the title column.
 *
 * @param  object result
 * @param  object info
 * @access public
 * @return object
 */
window.onRenderCell = function(result, {row, col})
{
    if(result)
    {
        if(col.name == 'caseID' && row.data.isScene)
        {
            result.shift(); // 移除场景ID
        }
        if(col.name == 'title')
        {
            const data = row.data;
            const module = this.options.customData.modules[data.module];
            if(data.color) result[0].props.style = 'color: ' + data.color;
            if(data.isScene) // 场景
            {
                result.shift(); // 移除带链接的场景名称
                result.push({html: data.title}); // 添加不带链接的场景名称
                if(data.grade == 1 && module) result.unshift({html: '<span class="label gray-pale rounded-full">' + module + '</span>'}); // 顶级场景添加模块标签
                result.unshift({html: '<span class="label gray-300-outline text-gray rounded-full">' + scene + '</span>'}); // 添加场景标签
                if(!this.options.customData.isOnlyScene && data.hasCase == false) result.push({html: '<span class="text-gray">(' + noCase + ')</span>'}); // 添加暂无用例标签
            }
            else // 用例
            {
                if(data.auto == 'auto') result.unshift({html: '<span class="label gray-pale rounded-full">' + automated + '</span>'}); // 添加自动化标签
                if(module) result.unshift({html: '<span class="label gray-pale rounded-full">' + module + '</span>'}); // 添加模块标签
            }
        }
        if(col.name == 'pri' && row.data.isScene)
        {
            result.shift(); // 移除场景优先级
        }
    }

    if(row.data.lastEditedDate == '0000-00-00 00:00:00') row.data.lastEditedDate = '';
    if(row.data.reviewedDate == '0000-00-00') row.data.reviewedDate = '';

    return result;
}

/**
 * 计算表格信息的统计。
 * Set summary for table footer.
 *
 * @param  element element
 * @param  array   checks
 * @access public
 * @return object
 */
window.setStatistics = function(element, checks)
{
    if(checks.length)
    {
        caseCount    = 0;
        runCaseCount = 0;
        checks.forEach((id) => {
            const scene = element.getRowInfo(id).data;
            if(scene.isScene == false)
            {
                caseCount ++;
                if(scene.lastRunResult != '') runCaseCount ++;
            }
        });
        return zui.formatString(checkedSummary, {
            checked: caseCount,
            run: runCaseCount
        });
    }

    return element.options.customData ? element.options.customData.pageSummary : '';
}

/**
 * Get selected case id list.
 *
 * @access public
 * @return void
 */
function getCheckedCaseIdList()
{
    let caseIdList = '';

    const dtable = zui.DTable.query('#table-testcase-browse');
    $.each(dtable.$.getChecks(), function(index, caseID)
    {
        if(index > 0) caseIdList += ',';
        caseIdList += caseID;
    });
    $('#caseIdList').val(caseIdList);
}

/**
 * Check ztf script run result.
 *
 * @param  e
 * @access public
 * @return void
 */
window.checkZtf = function(e)
{
    e.preventDefault();
    e.stopPropagation();

    const url = $(this).attr('href');
    $.get(url, function(result)
    {
        const load = result.load;
        if(!load || typeof load == 'string')
        {
            zui.Modal.open({url: load, size: 'lg', replace: true});
            return false;
        }

        zui.Modal.confirm(load.confirm).then((res) => {
            if(!res) return loadModal(load.canceled, null, {size: 'lg'});

            $.post(load.confirmed, {}, function(result)
            {
                result = JSON.parse(result);
                if(result.result == 'fail') return zui.Modal.alert(result.message);

                loadModal(load.canceled, null, {size: 'lg'});
            });
        });
    }, 'json');
}

window.clickChangeScenen = function()
{
    const caseID  = $('#dragModal').attr('data-sourceID');
    const sceneID = $('#dragModal').attr('data-targetID');

    changeScene(caseID, sceneID);
}

window.clickChangeOrder = function()
{
    const sourceID    = $('#dragModal').attr('data-sourceID');
    const sourceOrder = $('#dragModal').attr('data-sourceOrder');
    const targetID    = $('#dragModal').attr('data-targetID');
    const targetOrder = $('#dragModal').attr('data-targetOrder');
    const type        = $('#dragModal').attr('data-type');

    changeOrder(sourceID, sourceOrder, targetID, targetOrder, type, 'scene');
}

$('#dragModal').on('click', '[data-dismiss=modal]', function()
{
    loadCurrentPage();
});
