let progressTemplate = '<div class="progress rounded-lg w-40 h-2 mr-2 inline-block" style="width:120px;" title="{tip}"><div class="progress-bar {color}" style="width: {rate};"></div></div>';
let statusTemplate   = '<span id="instance-status-{id}" data-status="{status}" class="{class}">{text}</span>';
let cpuCircle, memoryCircle, cpuRate, memoryRate, cpuAnimationId, memoryAnimationId, cpuRateStart = 0, memoryRateStart = 0;

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
    loadCneStatistic();
    if(instanceIdList.length === 0) return;
    if(inQuickon) timer = setInterval(refreshStatus, 5000);
});

window.onPageUnmount = function()
{
    if(typeof timer !== 'undefined') clearInterval(timer);
}

function loadCneStatistic()
{
    toggleLoading('#cpu-circle',    true);
    toggleLoading('#memory-circle', true);
    toggleLoading('#status-icon',   true);
    toggleLoading('.cpu-rate',      true);
    toggleLoading('.cpu-memory',    true);
    toggleLoading('#cne-statistic .cne-status',    true);
    toggleLoading('#cne-statistic .node-quantity', true);
    $.ajaxSubmit({
        url: $.createLink('system', 'ajaxCneMetrics'),
        onComplete:function(res)
        {
            if(res.result === 'success')
            {
                let cpuInfo    = res.data.cpuInfo, memoryInfo = res.data.memoryInfo, cpuLoading = true, memoryLoading = true;
                cpuInfo.tip    = cpuInfo.tip.substring(cpuInfo.tip.indexOf('=') + 1).trim();
                memoryInfo.tip = memoryInfo.tip.substring(memoryInfo.tip.indexOf('=') + 1).trim();
                cpuRate        = cpuInfo.rate;
                memoryRate     = memoryInfo.rate;
                if(typeof $('#cpu-circle').zui() === 'undefined')
                {
                    $('#cpu-circle div').remove();
                }
                else
                {
                    cpuLoading = false;
                    $('#cpu-circle').remove();
                    $('.cpu-circle').prepend('<div id="cpu-circle" class="relative"><span class="absolute text-lg" style="transform: translate(-50%, -50%); top: 50%; left: 50%; white-space: nowrap;"><i class="icon icon-cpu mr-1" style="font-size: 20px; color: gray;"></i>' + cpuUsage + '</span></div>')
                }

                if(typeof $('#memory-circle').zui() === 'undefined')
                {
                    $('#memory-circle div').remove();
                }
                else
                {
                    memoryLoading = false;
                    $('#memory-circle').remove();
                    $('.memory-circle').prepend('<div id="memory-circle" class="relative"><span class="absolute text-lg" style="transform: translate(-50%, -50%); top: 50%; left: 50%; white-space: nowrap;"><i class="icon icon-memory mr-1" style="font-size: 20px; color: gray;"></i>' + memUsage + '</span></div>')
                }

                cpuCircle    = new zui.ProgressCircle('#cpu-circle',    {percent: 0, size: 160, circleColor: cpuInfo.color,    circleWidth: 8, text: ''});
                memoryCircle = new zui.ProgressCircle('#memory-circle', {percent: 0, size: 160, circleColor: memoryInfo.color, circleWidth: 8, text: ''});
                $('#status-icon').replaceWith('<i class="icon icon-exclamation-pure app-status-circle icon-' + statusIcons[res.data.status] + ' status-' + res.data.status + ' " style="font-size: 30px;"></i>');
                $('#cne-statistic .cne-status').text(statusList[res.data.status]);
                $('#cne-statistic .node-quantity').text(res.data.node_count);
                $('#cne-statistic .icon-cpu').css('color' , cpuInfo.color);
                $('#cne-statistic .icon-memory').css('color' , memoryInfo.color);
                $('.cpu-rate').html(cpuInfo.rate + '<span class="text-xl ml-1">%</span>');
                $('.memory-rate').html(memoryInfo.rate + '<span class="text-xl ml-1">%</span>');
                let element = document.querySelector(".cpu-rate"),textNode = document.createTextNode(cpuInfo.tip);
                element.parentNode.insertBefore(textNode, element.nextSibling);
                element  = document.querySelector(".memory-rate");
                textNode = document.createTextNode(memoryInfo.tip);
                element.parentNode.insertBefore(textNode, element.nextSibling);
                toggleLoading('#cne-statistic .cne-status',    false);
                toggleLoading('#cne-statistic .node-quantity', false);
                if(cpuLoading)    toggleLoading('#cpu-circle',    false);
                if(memoryLoading) toggleLoading('#memory-circle', false);
                toggleLoading('.cpu-rate',   false);
                toggleLoading('.cpu-memory', false);
                loadProgressCircle();
            }
            else
            {
                toggleLoading('#cpu-circle',    false);
                toggleLoading('#memory-circle', false);
                toggleLoading('#status-icon',   false);
                toggleLoading('.cpu-rate',      false);
                toggleLoading('.cpu-memory',    false);
                toggleLoading('#cne-statistic .cne-status',    false);
                toggleLoading('#cne-statistic .node-quantity', false);
            }
        }
    });
}
function loadProgressCircle()
{
    loadCpuProgressCircle();
    loadMemoryProgressCircle();
}
function loadCpuProgressCircle()
{
    if(++cpuRateStart >= Math.floor(cpuRate))
    {
        cpuCircle.render({percent: cpuRate});
        cancelAnimationFrame(cpuAnimationId);
    }
    else if(cpuRateStart < Math.floor(cpuRate))
    {
        cpuCircle.render({percent: cpuRateStart});
        cpuAnimationId = requestAnimationFrame(loadCpuProgressCircle);
    }
}
function loadMemoryProgressCircle()
{
    if(++memoryRateStart >= Math.floor(memoryRate))
    {
        memoryCircle.render({percent: memoryRate});
        cancelAnimationFrame(memoryAnimationId);
    }
    else if(memoryRateStart < Math.floor(memoryRate))
    {
        memoryCircle.render({percent: memoryRateStart});
        memoryAnimationId = requestAnimationFrame(loadMemoryProgressCircle);
    }
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
                    if($("#instance-status-" + instance.id).data('status') != instance.status)
                    {
                        loadTable();
                        return;
                    }
                });
            }
        }
    });
}
