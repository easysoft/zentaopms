const today = zui.formatDate(new Date(), 'yyyy-MM-dd');
window.onRenderExecutionCell = function(result, info)
{
    if(info.col.name === 'name' && (systemMode == 'ALM' || systemMode == 'PLM'))
    {
        const executionLink = $.createLink('execution', 'task', `executionID=${info.row.id}`);
        const executionType = typeList[info.row.data.type];

        let executionName   = `<span class='label secondary-pale flex-none'>${executionType}</span> `;
        executionName      += '<div class="ml-1 clip" style="width: max-content;">';
        executionName      += (!info.row.data.isParent && typeof result[0] != 'string') ? `<a href="${executionLink}" class="text-primary">${info.row.data.name}</a>` : info.row.data.name;
        executionName      += '</div>';
        executionName      += (!['done', 'closed', 'suspended'].includes(info.row.data.status) && today > info.row.data.end) ? '<span class="label danger-pale ml-1 flex-none">' + delayWarning.replace('%s', info.row.data.delay) + '</span>' : '';
        result[0] = {html: executionName, className: 'w-full flex items-center'};

        return result;
    }

    return result;
}
