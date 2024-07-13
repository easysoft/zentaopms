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

    getRemoteScript(jsRoot + 'js/html2canvas/min.js', function()
    {
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
    }, errorCallback);
}
