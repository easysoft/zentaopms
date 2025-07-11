$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();

    checkedList.forEach((id) => {
        const data = dtable.$.getRowInfo(id).data;
        form.append('sceneIdList[]', data.id);
    });

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({ url, data: form });
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
    return !!from && !!to;
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
    if(result && col.name == 'title')
    {
        const data   = row.data;
        const module = this.options.customData.modules[data.module];
        if(module) result.unshift({ html: '<span class="label gray-pale rounded-full nowrap">' + module + '</span>' }); // 顶级场景添加模块标签
        result.unshift({ html: '<span class="label gray-300-outline text-gray rounded-full nowrap">' + scene + '</span>' }); // 添加场景标签
    }

    if(row.data.lastEditedDate == '0000-00-00 00:00:00') row.data.lastEditedDate = '';

    return result;
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
    const sourceOrder = $('#dragModal').attr('data-sourceSort');
    const targetID    = $('#dragModal').attr('data-targetID');
    const targetOrder = $('#dragModal').attr('data-targetSort');
    const type        = $('#dragModal').attr('data-type');

    changeOrder(sourceID, sourceOrder, targetID, targetOrder, type, 'scene');
}

$('#dragModal').on('click', '[data-dismiss=modal]', function()
{
    loadCurrentPage({cache: false});
});