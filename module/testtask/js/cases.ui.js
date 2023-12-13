$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query();
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url   = $(this).data('url');
    const form  = new FormData();
    let autoRun = false;
    checkedList.forEach((id) => {
        const caseInfo = dtable.$.getRowInfo(id).data;
        form.append('caseIdList[]', caseInfo.case);
        if(caseInfo.auto == 'auto') autoRun = true;
    });

    if($(this).hasClass('batch-run') && autoRun)
    {
        zui.Modal.confirm({message: runCaseConfirm, onResult: function(result)
        {
            if(result)
            {
                const ztfURL = $.createLink('zanode', 'ajaxRunZTFScript', 'scriptID=' + automation);
                $.post(ztfURL, form, function(result)
                {
                    if(result.result == 'fail')
                    {
                        zui.Modal.alert(result.message);
                        return false;
                    }

                    if($(this).hasClass('ajax-btn'))
                    {
                        $.ajaxSubmit({url, data: form});
                    }
                    else
                    {
                        postAndLoadPage(url, form);
                    }
                }, 'json');
            }
        }});
    }
    else
    {
        if($(this).hasClass('ajax-btn'))
        {
            $.ajaxSubmit({url, data: form});
        }
        else
        {
            postAndLoadPage(url, form);
        }
    }
});

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
        if(col.name == 'title')
        {
            const data = row.data;
            const module = this.options.customData.modules[data.module];
            if(module) result.unshift({html: '<span class="label lighter rounded-full">' + module + '</span>'}); // 添加模块标签
        }
    }

    return result;
}
