$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query(this);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url   = $(this).data('url');
    const form  = new FormData();
    let autoRun = false;
    checkedList.forEach((id) => {
        form.append('caseIdList[]', id);
        const caseInfo = dtable.$.getRowInfo(id).data;
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
