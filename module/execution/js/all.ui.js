$(document).off('click','.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const $target = $(this);
    if($target.hasClass('batch-close-btn'))
    {
        const getNonClosableLink = $.createLink('execution', 'ajaxGetNonClosableExecutions', 'executionID=' + checkedList.join(','));
        $.getJSON(getNonClosableLink, function(exeuctions)
        {
            if(!exeuctions) return;

            const confirmCloseTip = confirmBatchCloseExecution.replace('%s', exeuctions.join(', '));
            zui.Modal.confirm(confirmCloseTip).then((res) =>
            {
                if(res) postBatchBtn($target, checkedList);
            });
        });
    }
    else
    {
        postBatchBtn($target, checkedList);
    }
});

const today = zui.formatDate(new Date(), 'yyyy-MM-dd');
window.onRenderCell = function(result, {col, row})
{
    if(col.name == 'nameCol')
    {
        const executionLink = $.createLink('execution', 'task', `executionID=${row.data.rawID}`);
        const executionType = typeList[row.data.type];

        let executionName   = `<span class='label secondary-pale flex-none'>${executionType}</span> `;
        executionName      += '<div class="ml-1 clip" style="width: max-content;">';
        executionName      += (!row.data.isParent) ? `<a href="${executionLink}" class="text-primary">${row.data.name}</a>` : row.data.name;
        executionName      += '</div>';
        executionName      += (!['done', 'closed', 'suspended'].includes(row.data.status) && today > row.data.end) ? `<span class="label danger-pale ml-1 flex-none">${delayed}</span>` : '';

        result.push({html: executionName, className: 'w-full flex items-center'});
        return result;
    }
    if(['estimate', 'consumed','left'].includes(col.name) && result) result[0] = {html: result[0] + ' h'};

    return result;
}

function postBatchBtn($target, checkedList)
{
    const url  = $target.data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('executionIDList[]', id.replace("pid", '')));

    if($target.hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data: form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
}
