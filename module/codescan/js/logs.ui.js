window.unescapeHtml = function(str)
{
    if (typeof str !== 'string') return '';

    const entityMap = {
        escape: {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            "'": '&#039;',
        },
        unescape: {
            '&amp;': '&',
            '&lt;': '<',
            '&gt;': '>',
            '&quot;': '"',
            '&#39;': "'",
            '&#039;': "'"
        }
    };
    return str.replace(/&lt;|&gt;|&amp;|&quot;|&#39;|&#039;/g, (match) => entityMap.unescape[match]);
};

window.openPipelineLog = function(logID, pipelineName = '', executionID = '')
{
    let params = logParams.replace('%logID%', logID);
    if(pipelineName) params = params.replace('%pipelineName%', pipelineName.replace(/-/, '*'));
    if(executionID)  params = params.replace('%executionID%', executionID);

    $.getJSON($.createLink(moduleName, methodName, params), function(logs)
    {
        $.getLib(config.webRoot + 'js/misc/highlight/highlight.pack.js', {root: false}, function()
        {
            hljs.initHighlightingOnLoad();

            let {value} = hljs.highlight('bash', logs.data);
            value = unescapeHtml(value);
            value = `${value.replace(/Job: (.+?),/, 'Job: <span class="job-title">$1</span>,')
                    .replace(/(&gt;){3} Step/g, '<br> >>> Step')
                    .replace(/Step: (.+?),/g, 'Step: <span class="step-title">$1</span>,')
                    .replace(/Status: (\w+),/g, 'Status: <span class="status-$1">$1</span>,')
                    .replace(/Duration: (.+) Sec/g, 'Duration: <strong>$1 Sec</strong>')}`;

            $('#pipelineLog').html(value);
            $('#pipelineLogBlock').addClass('open');
        });
    });
};

window.closePipelineLog = function(event)
{
    if(logID) return;

    const $dom = $(event.target);
    if($dom.closest('#pipelineLog').length) return;
    if($dom.closest('.toolbar').length) return;
    if($dom.closest('.log-header').length && !$dom.hasClass('btn') && !$dom.hasClass('icon')) return;

    $('#pipelineLogBlock').removeClass('open');
};

$(`.m-${config.currentModule}-${config.currentMethod}`).off('click').on('click', window.closePipelineLog);
