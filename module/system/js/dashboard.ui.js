var baseFontSize     = parseInt($("html").css("font-size"), 10);
var progressTemplate = '<div class="progress rounded-lg w-40 h-2 mr-2 inline-block" style="width:120px;" title="{tip}"><div class="progress-bar {color}" style="width: {rate};"></div></div>';
var statusTemplate   = '<span id="instance-status-{id}" data-status="{status}" class="{class}">{text}</span>';
var enableTimer      = false;

const cpuProgressCircle = new zui.ProgressCircle('#progressCpu', {
    percent: cpuInfo.rate,
    size: baseFontSize * 10,
    circleColor: cpuInfo.color,
    circleWidth: 8,
    text: '',
});

const memoryProgressCircle = new zui.ProgressCircle('#progressMemory', {
    percent: memoryInfo.rate,
    size: baseFontSize * 10,
    circleColor: memoryInfo.color,
    circleWidth: 8,
    text: '',
});

window.renderInstanceList = function (result, {col, row, value})
{
    if(col.name === 'status')
    {
        var statusClass = value == 'running' ? 'text-success' : '';
        result[0] = {html: statusTemplate.replace('{id}', row.data.id).replace('{status}', row.data.status).replace('{text}', result[0]).replace('{class}', statusClass)};
        return result;
    }

    if(col.name === 'cpu')
    {
        var rowHtml = progressTemplate.replace('{color}', row.data.cpu.color).replace('{tip}', row.data.cpu.tip).replace('{rate}', row.data.cpu.rate)
        result[0] = {html: rowHtml + '<span class="text-gray">' + row.data.cpu.rate + '</span>'};
        return result;
    }

    if(col.name === 'mem')
    {
        var rowHtml = progressTemplate.replace('{color}', row.data.mem.color).replace('{tip}', row.data.mem.tip).replace('{rate}', row.data.mem.rate)
        result[0] = {html: rowHtml + '<span class="text-gray">' + row.data.mem.usage + '/' + row.data.mem.limit + '</span>'};
        return result;
    }

    return result;
}

$(function()
{
    if(typeof timer !== 'undefined') clearInterval(timer);
    if(instanceIdList.length === 0) return;
    if(inQuickon) timer = setInterval(refreshStatus, 5000);
});

window.onPageUnmount = function()
{
    if(typeof timer !== 'undefined') clearInterval(timer);
}

function refreshStatus()
{
    const postData  = new FormData();
    if(instanceIdList.length > 0)
    {
        instanceIdList.forEach(function(id){postData.append('idList[]', id)});
    }

    $.ajaxSubmit({
        url: $.createLink('instance', 'ajaxStatus'),
        method: 'POST',
        data: postData,
        onComplete: function(res)
        {
            if(res.result === 'success')
            {
                $.each(res.data, function(index, instance)
                {
                    if(statusMap[instance.id] != instance.status)
                    {
                        loadTable();
                        statusMap[instance.id] = instance.status;
                        return;
                    }
                });
            }
        }
    });
}
