/**
 * 拖拽用例。
 * Drag case.
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

    const url  = $.createLink('testcase', 'updateOrder');
    const form = new FormData();
    form.append('sourceID',    from.data.id);
    form.append('sourceOrder', from.data.sort);
    form.append('targetID',    to.data.id);
    form.append('targetOrder', to.data.sort);
    form.append('type', type);
    form.append('module', 'case');
    $.ajaxSubmit({url, data:form});
    return true;
}

/**
 * 拖拽的用例是否允许放下。
 * Is it allowed to drop the dragged case.
 *
 * @param  from   被拿起的元素
 * @param  to     放下时的目标元素
 * @access public
 * @return bool
 */
window.canSortTo = function(from, to)
{
    if(!from || !to) return false;
    return true;
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
        if(row.data.color) result[0].props.style = 'color: ' + row.data.color;
        const module = this.options.modules[row.data.module];
        if(module) result.unshift({html: '<span class="label gray-pale rounded-full">' + module + '</span>'}); // 添加模块标签
    }

    return result;
}
