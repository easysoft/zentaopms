let scriptLoadedMap   = {};

/**
 * Get remote script for export.
 *
 * @param  string $url
 * @param  function $successCallback
 * @param  function $errorCallback
 * @access public
 * @return void
 */
function getRemoteScript(url, successCallback, errorCallback)
{
    if(scriptLoadedMap[url]) return successCallback && successCallback();

    scriptLoadedMap[url] = true;

    let script = document.createElement('script');
    script.type = 'text/javascript';
    script.onload = function() { successCallback(); };
    script.src = url;
    document.head.appendChild(script);
}

/**
 * Update export progress.
 *
 * @param  int $progress
 * @access public
 * @return void
 */
function updateProgress(progress)
{
    let progressText = ganttLang.exporting;
    if(progress < 1) progressText += Math.floor(progress * 100) + '%';
    $('#mainContent').attr('data-loading', progressText);
}

/**
 * Draw gantt to canvas.
 *
 * @param  string   $exportType
 * @param  function $successCallback
 * @param  function $errorCallback
 * @access public
 * @return void
 */
function drawGanttToCanvas(exportType, successCallback, errorCallback)
{
    updateProgress(0);

    exportType = exportType || 'image';
    let $ganttView      = $('#' + ganttID);
    let oldHeight       = $ganttView.css('height');
    let $ganttContainer = $('#ganttContainer');
    let $ganttDataArea  = $ganttView.find('.gantt_data_area');
    let $ganttDridData  = $ganttView.find('.gantt_grid_data');

    let ganttHeadHeight = 40;
    let ganttRowHeight  = $ganttView.find('.gantt_row').first().height() || 25;
    let ganttHeight     = ganttHeadHeight + (options['data'].length ? options['data'] .length : 0) * (ganttRowHeight + 3);
    let ganttWidth      = $ganttDridData.width();
    if($ganttDataArea.length > 0) ganttWidth += $ganttDataArea.width();

    $ganttContainer.css(
    {
        height: ganttHeight + 80,
        width: ganttWidth + 93
    });
    $ganttView.css('height', ganttHeight);

    gantt.render();
    $ganttView.find('.gantt_grid_scale').height(ganttHeadHeight);
    $ganttView.find('.gantt_task_scale').height(ganttHeadHeight);
    $ganttView.find('.gantt_task_scale').find('.gantt_scale_line').height(ganttHeadHeight / 2);
    $ganttView.find('.gantt_task_scale').find('.gantt_scale_line .gantt_scale_cell').height(ganttHeadHeight / 2);
    $ganttView.find('.gantt_layout.gantt_layout_y.gantt_layout_cell_border_right').width($ganttDridData.width());
    $ganttView.find('.gantt_layout.gantt_layout_y.gantt_layout_cell_border_right > .grid_cell').width($ganttDridData.width());

    updateProgress(0.1);
    getRemoteScript(jsRoot + 'js/html2canvas/min.js', function()
    {
        updateProgress(0.2);
        let afterFinish = function(canvas)
        {
            $ganttContainer.css({width: '', height: ''});
            $ganttView.css('height', oldHeight);
            $('#mainContent #ganttDownload').remove();
            if(!canvas) return;

            try
            {
                canvas.removeNode(true)
            }
            catch(err)
            {
                canvas.remove()
            };
        };
        let delayTime = Math.max(1000, Math.floor(10 * (ganttHeight * ganttWidth) / 100000));
        let progressTimer;
        if(delayTime > 1500)
        {
            let startProgress = 0.2;
            let deltaProgress = 0.5 / Math.floor(delayTime/1000);
            progressTimer = setInterval(function()
            {
                startProgress += deltaProgress;
                updateProgress(Math.min(0.7, startProgress));
            }, 1000);
        }
        setTimeout(function()
        {
            if(progressTimer) clearInterval(progressTimer);
            updateProgress(0.7);
            html2canvas($ganttContainer[0], {logging: false, scale: 1}).then(function(canvas)
            {
                let isExportPDF = exportType === 'pdf';
                updateProgress(isExportPDF ? 0.8 : 0.9);
                canvas.onerror = function()
                {
                    afterFinish(canvas);
                    if(errorCallback) errorCallback('Cannot convert image to blob.');
                };
                if(isExportPDF)
                {
                    let width = canvas.width;
                    let height = canvas.height;
                    getRemoteScript(jsRoot + 'js/pdfjs/min.js', function()
                    {
                        updateProgress(0.8);
                        let pdf = new jsPDF(
                        {
                            format: [width, height],
                            unit: 'px',
                            orientation: width > height ? 'l' : 'p'
                        });
                        pdf.addImage(canvas, 0, 0, pdf.internal.pageSize.getWidth(), pdf.internal.pageSize.getHeight());
                        pdf.save(fileName + '.pdf');
                        if(successCallback) successCallback(pdf);
                        afterFinish();
                    }, function(error)
                    {
                        afterFinish(canvas);
                        if(errorCallback) errorCallback(error);
                    });
                }
                else
                {
                    canvas.toBlob(function(blob)
                    {
                        updateProgress(0.95);
                        let imageUrl = URL.createObjectURL(blob);
                        if(navigator.msSaveBlob)
                        {
                            navigator.msSaveOrOpenBlob(blob, fileName + '.png');
                        }
                        else
                        {
                            $('#mainContent').append('<a id="ganttDownload" style="display:none;" download="' + fileName + '.png" target="_blank" href="' + imageUrl + '"></a>');
                            $('#mainContent #ganttDownload')[0].click();
                        }
                        if(successCallback) successCallback(imageUrl);
                        afterFinish(canvas);
                    });
                }
            }).catch(function(error)
            {
                afterFinish();
                if(errorCallback) errorCallback('Cannot draw graphic to canvas.');
            });
        }, delayTime);
    }, errorCallback);
}

/**
 * Export gantt.
 *
 * @param  string $exportType
 * @access public
 * @return void
 */
window.exportGantt = function(exportType)
{
    let $mainContent = $('#mainContent');
    $mainContent.addClass('load-indicator').addClass('loading').css('height', Math.max(200, Math.floor($(window).height() - $('#header').outerHeight() - $('#mainMenu').outerHeight())));
    $('#main').removeClass('load-indicator');
    let afterFinish = function(url)
    {
        setTimeout(function()
        {
            $mainContent.css('height', '').removeClass('loading').removeClass('load-indicator');
            $('#main').addClass('load-indicator');
        }, 300);
        updateProgress(1);
    };
    drawGanttToCanvas(exportType, afterFinish, function(errorText)
    {
        afterFinish();
        zui.Messager.show({content: ganttLang.exportFail + (errorText || ''), type: 'danger-outline'});
    });
}

/**
 * Get by id for gantt.
 *
 * @param  array  $list
 * @param  string $id
 * @access public
 * @return string
 */
function getByIdForGantt(list, id)
{
    for(let i = 0; i < list.length; i++)
    {
        if (list[i].key == id) return list[i].label || "";
    }
    return id;
}

/**
 * Zoom tasks.
 *
 * @param  value
 * @access public
 * @return void
 */
function zoomTasks(value)
{
    switch(value)
    {
        case "day":
            gantt.config.min_column_width = 70;
            gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'day', step: 1, format: '%m-%d'}];
            gantt.config.scale_height = 22 * gantt.config.scales.length;
        break;
        case "week":
            gantt.config.min_column_width = 70;
            gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'week', step: 1, format: ganttLang.zooming['week'] + " #%W"}, {unit:"day", step:1, date:"%D"}]
            gantt.config.scale_height = 22 * gantt.config.scales.length;
        break;
        case "month":
            gantt.config.min_column_width = 70;
            gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'month', step: 1, format: '%M'}, {unit:"week", step:1, date: ganttLang.zooming['week'] + " #%W"}];
            gantt.config.scale_height = 22 * gantt.config.scales.length;
        break;
    }

    gantt.render();
    $('.gantt_grid_head_cell .sort').addClass(value);
}

/**
 * Update criticalPath
 *
 * @access public
 * @return void
 */
function updateCriticalPath()
{
    let showCriticalPath = !gantt.config.highlight_critical_path;

    $('#criticalPath').html(showCriticalPath ? ganttLang.hideCriticalPath : ganttLang.showCriticalP);
    gantt.config.highlight_critical_path = showCriticalPath;

    gantt.render();
}

function exitHandler()
{
    if (module == 'review' && method == 'assess' && !document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement)
    {
        loadCurrentPage();
    }
}

/* Validate task drag. */
function validateResources(id)
{
    let task = gantt.getTask(id);
    let from = new Date(task.start_date);
    let to   = new Date(task.end_date);
    let status = task.status;
    let type   = task.type;
    let statusLang = ganttLang.taskStatusList;
    flag = true;

    /* Check status. */
    if(status !== statusLang && type != 'point')
    {
        let tipMsg = ganttLang.errorPlanDrag;
        if(type == 'task') tipMsg = ganttLang.errorTaskDrag;

        zui.Messager.show({content: tipMsg.replace('%s', status), type: 'danger-outline', icon: 'exclamation-sign'});
        gantt.refreshData();
        return false;
    }

    task.begin    = from;
    task.deadline = (new Date(to.valueOf() - 1));;

    gantt.updateTask(task.id);
    let itemID;
    if(type == 'task')  itemID = task.id.split("-")[1];
    if(type == 'plan')  itemID = task.id;
    if(type == 'point') itemID = task.id.split("-")[2];
    let postData = {
        'id'        : itemID,
        'startDate' : from.toLocaleDateString('en-CA'),
        'endDate'   : to.toLocaleDateString('en-CA'),
        'type'      : type
    };
    $.ajax({
        url: $.createLink('programplan', 'ajaxResponseGanttDragEvent'),
        dataType: "json",
        data: postData,
        type: "post",
        success: function(response)
        {
            if(response.result == 'fail' && response.message)
            {
                zui.Messager.show({content: response.message, type: 'danger-outline', icon: 'exclamation-sign'});
                flag = false;
            }
        }
    });

    return flag;
}
