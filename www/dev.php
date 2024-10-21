<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Zentao Dev Tools</title>
<link id="zuiCSS" rel="stylesheet" href="./js/zui3/zui.zentao.css">
<script id="zuiJS" src="./js/zui3/zui.zentao.js"></script>
<style>
.dark {--zt-page-bg: var(--color-gray-50)}
.dtable {--dtable-header-bg: var(--color-special-50); --dtable-border-color: rgba(var(--color-fore-rgb), .1)}
</style>
</head>
<body>
<div class="mx-4 mt-4 space-y-2">
  <div class="row items-center gap-4">
    <h1>Perf guard data</h1>
    <div class="switch mt-1">
      <input type="checkbox" id="autoRefresh">
      <label for="autoRefresh">Auto Refresh</label>
    </div>
    <div class="flex-auto"></div>
    <div class="switch mt-1">
      <input type="checkbox" id="themeMode">
      <label for="themeMode"><i class="icon icon-moon" style="position: absolute; left: 16px; top: 4px; font-size: 12px; opacity: 0.5;"></i></label>
    </div>
  </div>
  <div class="row items-center gap-3 flex-wrap" id="queryForm">
    <div class="input-control has-prefix w-32" style="--input-control-prefix: 40px">
      <input id="identifier" type="text" class="form-control text-special" placeholder="git name">
      <label for="identifier" class="input-control-prefix">User</label>
    </div>
    <div class="input-control has-prefix w-36" style="--input-control-prefix: 60px">
      <input id="request_module" type="text" class="form-control text-special" placeholder="name">
      <label for="request_module" class="input-control-prefix">Module</label>
    </div>
    <div class="input-control has-prefix w-36" style="--input-control-prefix: 60px">
      <input id="request_method" type="text" class="form-control text-special" placeholder="name">
      <label for="request_method" class="input-control-prefix">Method</label>
    </div>
    <div class="input-control has-prefix" style="--input-control-prefix: 48px; width: 150px">
      <input id="timestamp__gt" type="date" class="form-control text-special" placeholder="">
      <label for="timestamp__gt" class="input-control-prefix">Begin</label>
    </div>
    <div class="input-control has-prefix" style="--input-control-prefix: 40px; width: 142px">
      <input id="timestamp__lt" type="date" class="form-control text-special" placeholder="">
      <label for="timestamp__lt" class="input-control-prefix">End</label>
    </div>
    <div class="input-control has-prefix" style="--input-control-prefix: 40px; width: 116px">
      <input id="requestId" type="text" class="form-control text-special" placeholder="Request ID">
      <label for="requestId" class="input-control-prefix">RID</label>
    </div>
    <div class="input-control has-prefix flex-auto" style="--input-control-prefix: 60px">
      <input id="params" type="text" class="form-control text-special" placeholder="e.g. metrics.backend.totalTime__gt=300&__limit=100">
      <label for="params" class="input-control-prefix">Params</label>
    </div>
    <button type="button" class="btn special" onclick="queryData()"><i class="icon icon-search"></i> Query <kbd class="code light-outline text-canvas text-sm opacity-50 shadow">↵</kbd></button>
  </div>
  <div class="load-indicator loading" id="table" style="height: calc(100vh - 116px);"></div>
</div>
<div style="display: none">
<?php
$config = new stdClass();
include '../config/config.php';
include '../config/my.php';

if(isset($config->zinTool['guardApi']))
{
    /* The next line is to support for using this php file as pure html file. */
    echo '<' . 'script' . ">window.guardApi = " . json_encode($config->zinTool['guardApi']) . ';window.guardUser = ' . json_encode($config->zinTool['author']) . ';' . '<' . '/script' . '>';
}
?>
</div>
<script>
$('#themeMode').on('change', () =>
{
    const isDark = $('#themeMode').is(':checked');
    $('body').toggleClass('dark', isDark);
    localStorage.setItem('themeMode', isDark ? 'dark' : 'light');
});
const localThemeMode = localStorage.getItem('themeMode');
if(localThemeMode === 'dark') $('#themeMode').trigger('click');
else if(localThemeMode !== 'light' && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) $('#themeMode').trigger('click');

window.userMap          = null;
let table               = null;
let autoRefreshTimer    = 0;
let autoRefreshInterval = 10000;
const $table            = $('#table');
const metricsLevelNames = ['', 'BLOCK', 'WARN', 'PASS'];

function getQueryUrl()
{
    const search = new URLSearchParams($('#params').val());
    const params = ['identifier', 'request.module', 'request.method', 'timestamp__gt', 'timestamp__lt', 'requestId'].forEach((key) =>
    {
        let val = $(`#${key.replace(/\./g, '_')}`).val();
        if(typeof val === 'string' && val.length)
        {
            if(key === 'timestamp__gt') val = zui.createDate(zui.formatDate(val, 'yyyy-MM-dd 00:00:00')).getTime();
            else if(key === 'timestamp__lt') val = zui.createDate(zui.formatDate(val, 'yyyy-MM-dd 23:59:59')).getTime();
            search.set(key, val);
        }
    });
    if(!search.has('__sort')) search.set('__sort', '-timestamp');
    if(!search.has('__limit')) search.set('__limit', 200);

    const searchString = search.toString();
    history.pushState(null, null, `${location.origin}${location.pathname}?${searchString}`);
    return `${window.guardApi}?${searchString}`;
}

function loadData()
{
    return fetch(getQueryUrl()).then(response => response.json());
}

function getTimeClass(time, danger = 400, warning = 100)
{
    if (time < warning) return '';
    if (time < danger) return 'warning';
    return 'danger';
}

function initTable(data)
{
    const cols =
    [
        {name: 'metricsLevel', title: 'LEVEL', hint: true, width: 60, fixed: 'left', cellClass: 'font-mono text-sm select-all', map: metricsLevelNames, hint: info => info.row.data.requestId, flex: 0, align: 'center', sort: 'number', border: 'right'},
        {name: 'identifier', type: Object.keys(window.userMap || {}).length ? 'avatarName' : '', title: 'User', fixed: 'left', flex: 0, width: 80, sort: true, avatarKey: 'identifier_avatar', avatarCodeKey: 'identifier_avatar_code', avatarNameKey: 'identifier_name'},
        {name: 'path', type: 'title', title: 'Page', hint: (info) => info.row.data.request.url, link: (info) => ({url: info.row.data.request.url, target: '_blank'}), flex: 1, fixed: 'left', maxWidth: 1000, sort: true},
        {name: 'metrics.backend.totalTime', title: 'Back Time', sort: 'number', type: 'number', digits: 2, format: '{0}ms', digits: 3, headerGroup: 'Backend', hint: info =>
        {
            const timeClass = info.row.data['metrics.backend.totalTimeClass'];
            if(timeClass === 'danger') return `BLOCK: > 500ms`;
            if(timeClass === 'warning') return `WARN: > 300ms`;
        }},
        {name: 'metrics.backend.sqlTime', title: 'SQL Time', sort: 'number', format: '{0}ms', digits: 3, headerGroup: 'Backend', hint: info =>
        {
            const timeClass = info.row.data['metrics.backend.sqlTimeClass'];
            if(timeClass === 'danger') return `BLOCK: > 300ms`;
            if(timeClass === 'warning') return `WARN: > 200ms`;
        }},
        {name: 'metrics.backend.sqlCount', title: 'SQLs', sort: 'number', width: 48, link: '#', hint: 'Click to check details', headerGroup: 'Backend'},
        {name: 'metrics.backend.requestMemory', title: 'Memory', sort: 'number', format: (value) => value ? zui.formatBytes(value) : '', headerGroup: 'Backend'},
        {name: 'metrics.backend.phpFileLoaded', title: 'PHP Files', width: 50, sort: 'number', align: 'center', headerGroup: 'Backend'},
        {name: 'metrics.frontend.downloadSize', title: 'Transfer Size', sort: 'number', format: (value) => value ? zui.formatBytes(value) : '', headerGroup: 'Frontend'},
        {name: 'metrics.frontend.renderTime', title: 'Front Time', sort: 'number', format: '{0}ms', headerGroup: 'Frontend', border: 'left', hint: info =>
        {
            const timeClass = info.row.data['metrics.frontend.renderTimeClass'];
            if(timeClass === 'danger') return `BLOCK: > 100ms`;
            if(timeClass === 'warning') return `WARN: > 60ms`;
        }},
        {name: 'userEnv.browser', title: 'Client Browser', sort: true, align: 'left', headerGroup: 'Frontend'},
        {name: 'userEnv.system', title: 'Client OS', sort: true, align: 'center', headerGroup: 'Frontend'},
        {name: 'request.xhprof', title: 'Xhprof', link: (info) => ({url: info.row.data.request.xhprof || '#', target: '_blank'}), format: (value) => (value ? 'Open' : ''), hint: true, width: 40},
        {name: 'requestId', title: 'RID', hint: true, width: 80, cellClass: 'font-mono text-sm select-all', hint: info => info.row.data.requestId, flex: 0, align: 'left', fixed: 'right'},
        {name: 'timestamp', title: 'Date', type: 'datetime', hint: (info) => zui.formatDate(info.row.data.timestamp, 'yyyy-MM-dd hh:mm:ss'), width: 120, sort: 'number', fixed: 'right'},
    ];

    table = new zui.DTable('#table',
    {
        className: 'ring ring-gray shadow rounded',
        plugins: ['sort', 'sort-col', 'zentao', 'resize'],
        width: '100%',
        height: '100%',
        fixedLeftWidth: 300,
        minColWidth: 40,
        hoverCol: true,
        sort: true,
        sortBy: {name: 'timestamp', order: 'desc'},
        colResize: true,
        rowKey: 'requestId',
        footer: [function(result, layout) {
            const metricsStats = this.metricsStats || {};
            return [
                {html: `Total <strong>${layout.allRows.length}</strong>`, className: 'text-gray mr-4'},
                metricsStats.danger ? {html: `<div class="font-bold row items-center gap-2 rounded-full px-2 danger" data-toggle="tooltip" data-type="danger" data-title="Black Time &gt; 500ms or SQL time &gt; 300ms or Client time &gt; 100ms" data-placement="top-start"><i class="icon icon-alert"></i>BLOCK <strong>${metricsStats.danger}</strong> </div>`, className: 'text-danger mr-4'} : null,
                metricsStats.warning ? {html: `<div class="font-bold row items-center gap-2 rounded-full px-2 warning-pale" data-toggle="tooltip" data-type="warning" data-title="Black Time &gt; 300ms or SQL time &gt; 200ms or Client time &gt; 60ms" data-placement="top-start"><i class="icon icon-alert"></i>WARN <strong>${metricsStats.warning}</strong> </div>`, className: 'text-warning mr-4'} : null,
            ];
        }],
        rowConverter: function(row)
        {
            row.path                                = `${row.request.module}-${row.request.method}`;
            row['metrics.backend.totalTime']        = row.metrics.backend.totalTime;
            row['metrics.backend.sqlCount']         = row.metrics.backend.sqlCount;
            row['metrics.backend.sqlTime']          = row.metrics.backend.sqlTime * ((!row.dataVer || row.dataVer < 2) ? 1000 : 1);
            row['metrics.backend.requestMemory']    = row.metrics.backend.requestMemory;
            row['metrics.backend.phpFileLoaded']    = row.metrics.backend.phpFileLoaded;
            row['metrics.frontend.renderTime']      = row.metrics.frontend.renderTime;
            row['metrics.frontend.downloadSize']    = row.metrics.frontend.downloadSize;
            row['userEnv.browser']                  = row.userEnv.browser;
            row['userEnv.system']                   = row.userEnv.system;
            row['request.xhprof']                   = row.request.xhprof;

            const totalTimeClass  = getTimeClass(row.metrics.backend.totalTime || 0, 500, 300);
            const sqlTimeClass    = getTimeClass(row.metrics.backend.sqlTime || 0, 300, 200);
            const renderTimeClass = getTimeClass(row.metrics.frontend.renderTime || 0, 100, 60);
            const classList       = [totalTimeClass, sqlTimeClass, renderTimeClass];
            row['metrics.backend.totalTimeClass']   = totalTimeClass;
            row['metrics.backend.sqlTimeClass']     = sqlTimeClass;
            row['metrics.frontend.renderTimeClass'] = renderTimeClass;
            row.metricsClass = classList.includes('danger') ? 'danger' : (classList.includes('warning') ? 'warning' : '');
            row.metricsLevel = classList.includes('danger') ? 1 : (classList.includes('warning') ? 2 : 3);

            if(window.userMap)
            {
                const user = window.userMap[row.identifier];
                if(user)
                {
                    row.identifier_avatar      = user.avatar;
                    row.identifier_avatar_code = user.id;
                    row.identifier_name        = user.realname || user.account;
                }
            }

            if(row.metricsClass)
            {
                if(!this.metricsStats) this.metricsStats = {warning: 0, danger: 0};
                this.metricsStats[row.metricsClass]++;
            }
            return row;
        },
        cols: cols,
        data: data,
        onRenderCell: function(result, info)
        {
            const colName = info.col.name;
            const rowData = info.row.data;
            if(colName === 'metrics.backend.totalTime' && rowData['metrics.backend.totalTimeClass']) result.push({root: true, className: `text-${rowData['metrics.backend.totalTimeClass']}${rowData['metrics.backend.totalTimeClass'] === 'danger' ? ' font-bold bg-danger': ''}`});
            if(colName === 'metrics.backend.sqlTime' && rowData['metrics.backend.sqlTimeClass']) result.push({root: true, className: `text-${rowData['metrics.backend.sqlTimeClass']}${rowData['metrics.backend.sqlTimeClass'] === 'danger' ? ' font-bold bg-danger': ''}`});
            if(colName === 'metrics.frontend.renderTime' && rowData['metrics.frontend.renderTimeClass']) result.push({root: true, className: `text-${rowData['metrics.frontend.renderTimeClass']}${rowData['metrics.frontend.renderTimeClass'] === 'danger' ? ' font-bold bg-danger': ''}`});
            else if(colName === 'metricsLevel' && rowData.metricsLevel < 3) result.push({root: true, className: rowData.metricsClass === 'danger' ? 'font-bold bg-danger bg-opacity-100 text-canvas' : 'font-bold bg-warning text-warning bg-opacity-20'});
            if(rowData.metricsClass) result.push({root: true, className: `bg-${rowData.metricsClass} bg-opacity-${rowData.metricsClass === 'danger' ? 40 : 10}`});
            return result;
        },
        onCellClick: function(event, info)
        {
            if(info.colName === 'metrics.backend.sqlCount')
            {
                const sqlDetails = info.rowInfo.data.metrics.backend.sqlDetails;
                const oldVersion = !info.rowInfo.data.dataVer || info.rowInfo.data.dataVer < 2;
                if(!sqlDetails || !sqlDetails.length) return;
                zui.Modal.showError({
                    title: `SQL Details (${sqlDetails.length})`,
                    html: true,
                    size: 'lg',
                    mono: false,
                    error: [
                        '<table class="table w-full canvas ring shadow rounded-lg">',
                          '<thead>',
                            '<tr>',
                              '<th>ID</th>',
                              '<th class="w-32">Duration (ms)</th>',
                              '<th>Query</th>',
                            '</tr>',
                          '</thead>',
                          '<tbody>',
                          sqlDetails.map(detail => [
                              '<tr>',
                                `<td>${detail.Query_ID}</td>`,
                                `<td class="text-${getTimeClass(detail.Duration, 200, 100)}">${detail.Duration * (oldVersion ? 1000 : 1)}</td>`,
                                `<td class="font-mono text-sm select-all">${detail.Query}</td>`,
                              '</tr>',
                            ].join('\n')).join('\n'),
                          '</tbody>',
                        '</table>'
                    ].join('\n'),
                });
                return;
            }
            console.log('> clicked', info.rowInfo.data);
        }
    });
    console.log('> table', table);
}

function queryData(options)
{
    options = options || {};
    if(!options.noLoading) $table.addClass('loading');
    loadData().then(data =>
    {
        $table.removeClass('loading');
        if(table)
        {
            table.$.metricsStats = {warning: 0, danger: 0};
            return table.render({data});
        }
        initTable(data);
    }).catch(error => {
        $table.removeClass('loading');
        zui.Modal.showError({error: error});
        console.error(error);
    });
}

function getAPI()
{
    if(window.guardApi) return Promise.resolve(window.guardApi);
    return fetch('./dev.json').then(response => response.json()).then(json => {
        window.guardApi = json.api;
        if(json.user)    window.guardUser = json.user;
        if(json.userMap) window.userMap = json.userMap;
        return json.api;
    });
}

function setAutoRefreshTimer()
{
    if(autoRefreshTimer)
    {
        clearTimeout(autoRefreshTimer);
        autoRefreshTimer = 0;
    }
    if($('#autoRefresh').is(':checked'))
    {
        const interval = autoRefreshInterval || 10000;
        const tryLoad = () => {
            if($table.hasClass('loading')) return;
            queryData({noLoading: true});
        };
        autoRefreshTimer = setInterval(tryLoad, interval);
        tryLoad();
        $('#autoRefresh+label').text(`Auto Refresh (every ${(interval/1000)}s)`);
    }
    else $('#autoRefresh+label').text('Auto Refresh');
}

((function()
{
    const search = new URLSearchParams(window.location.search);
    const params = [];
    const keyMap = {
        user  : 'identifier',
        module: 'request.module',
        method: 'request.method',
        begin : 'timestamp__gt',
        end   : 'timestamp__lt',
        rid   : 'requestId',
    };
    for (const entry of search)
    {
        const key   = keyMap[entry[0]] || entry[0];
        const value = entry[1];
        if(key === 'api')
        {
            window.guardApi = value;
        }
        else if(key === 'autoRefresh')
        {
            autoRefreshInterval = +value;
            if(autoRefreshInterval) $('#autoRefresh').prop('checked', true);
        }
        else if(key === 'date')
        {
            const date = zui.createDate(value);
            $('#timestamp__gt').val(zui.formatDate(date, 'yyyy-M-d'));
            $('#timestamp__lt').val(zui.formatDate(date, 'yyyy-M-d'));
        }
        else if(key === 'params')
        {
            params.push(value);
        }
        else
        {
            const $ele = $(`#${key.replace(/\./g, '_')}`);
            if($ele.length)
            {
                $ele.val(key.startsWith('timestamp__') ? zui.formatDate(+value, 'yyyy-M-d') : value);
            }
            else params.push(`${key}=${encodeURIComponent(value)}`);
        }
        $('#params').val(params.join('&'));
    }

    const now = new Date();
    if(!$('#timestamp__gt').val()) $('#timestamp__gt').val(zui.formatDate(now, 'yyyy-M-d'));
    if(!$('#timestamp__lt').val()) $('#timestamp__lt').val(zui.formatDate(now, 'yyyy-M-d'));

    getAPI().then(() =>
    {
        if(window.guardUser && !$('#identifier').val()) $('#identifier').val(window.guardUser);
        queryData();
    });

    $('#queryForm').on('keyup', (event) => {if(event.key === 'Enter') queryData();});
    $('#timestamp__lt').on('change', event =>
    {
        const endDate = zui.createDate(event.target.value);
        const beginDate = zui.createDate($('#timestamp__gt').val());
        if(endDate < beginDate) $('#timestamp__gt').val(event.target.value);
    });
    $('#timestamp__gt').on('change', event =>
    {
        const beginDate = zui.createDate(event.target.value);
        const endDate = zui.createDate($('#timestamp__lt').val());
        if(beginDate > endDate) $('#timestamp__lt').val(event.target.value);
    });
    $('#autoRefresh').on('change', setAutoRefreshTimer);
    setAutoRefreshTimer();
})());
</script>
</body>
</html>
