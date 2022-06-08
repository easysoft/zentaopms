<?php
/**
 * The gantt of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: gantt.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php chdir(__DIR__);?>
<?php include '../../common/view/gantt.html.php';?>
<style>
#ganttView {height: 600px;}
#mainContent:before {background: #fff;}
.gantt-fullscreen #header,
.gantt-fullscreen #mainMenu,
.gantt-fullscreen #footer {display: none!important;}
.gantt-fullscreen #mainContent {position: fixed; top: 0; right: 0; bottom: 0; left: 0}
.gantt_task_content{display: none;}
.checkbox-primary {margin-top: 0px; margin-left: 10px;}
form {display: block; margin-top: 0em; margin-block-end: 1em;}
.gantt_task_progress {background: rgba(0,0,0,.1)}
#ganttPris > span {display: inline-block; line-height: 20px; min-width: 20px; border-radius: 2px;}
.gantt_task_line {background: #<?php echo $lang->execution->gantt->color[0]?>; border-color: #<?php echo $lang->execution->gantt->color[0]?>;}
.gantt_task_line.pri-1 {background: #<?php echo $lang->execution->gantt->color[1]?>; border-color: #<?php echo $lang->execution->gantt->color[1]?>}
.gantt_task_line.pri-2 {background: #<?php echo $lang->execution->gantt->color[2]?>; border-color: #<?php echo $lang->execution->gantt->color[2]?>}
.gantt_task_line.pri-3 {background: #<?php echo $lang->execution->gantt->color[3]?>; border-color: #<?php echo $lang->execution->gantt->color[3]?>}
.gantt_task_line.pri-4 {background: #<?php echo $lang->execution->gantt->color[4]?>; border-color: #<?php echo $lang->execution->gantt->color[4]?>}
.gantt_task_line.gantt_selected {box-shadow: 0 1px 1px rgba(0,0,0,.05), 0 2px 6px 0 rgba(0,0,0,.045)}
.gantt_link_arrow_right {border-left-color: #2196F3;}
.gantt_link_arrow_left {border-right-color: #2196F3;}
.gantt_task_link .gantt_line_wrapper div{background-color: #2196F3;}
.gantt_critical_link .gantt_line_wrapper>div {background-color: #e63030 !important;}
.gantt_critical_link.start_to_start .gantt_link_arrow_right {border-left-color: #e63030 !important;}
.gantt_critical_link.finish_to_start .gantt_link_arrow_right {border-left-color: #e63030 !important;}
.gantt_critical_link.start_to_finish .gantt_link_arrow_left {border-right-color: #e63030 !important;}
.gantt_critical_link.finish_to_finish .gantt_link_arrow_left {border-right-color: #e63030 !important;}
.gantt_task_link.start_to_start .gantt_line_wrapper div { background-color: #DD55EA; }
.gantt_task_link.start_to_start:hover .gantt_line_wrapper div { box-shadow: 0 0 5px 0px #DD55EA; }
.gantt_task_link.start_to_start .gantt_link_arrow_right { border-left-color: #DD55EA; }
.gantt_task_link.finish_to_start .gantt_line_wrapper div { background-color: #7576ba; }
.gantt_task_link.finish_to_start:hover .gantt_line_wrapper div { box-shadow: 0 0 5px 0px #7576ba; }
.gantt_task_link.finish_to_start .gantt_link_arrow_right { border-left-color: #7576ba; }
.gantt_task_link.finish_to_finish .gantt_line_wrapper div { background-color: #55d822; }
.gantt_task_link.finish_to_finish:hover .gantt_line_wrapper div { box-shadow: 0 0 5px 0px #55d822; }
.gantt_task_link.finish_to_finish .gantt_link_arrow_left { border-right-color: #55d822; }
.gantt_task_link.start_to_finish .gantt_line_wrapper div { background-color: #975000; }
.gantt_task_link.start_to_finish:hover .gantt_line_wrapper div { box-shadow: 0 0 5px 0px #975000; }
.gantt_task_link.start_to_finish .gantt_link_arrow_left { border-right-color: #975000; }
.gantt_marker .gantt_marker_content {left: -15px; background-color: #f51e1e;}
</style>
<?php js::set('customUrl', $this->createLink('programplan', 'ajaxCustom'));?>
<?php js::set('dateDetails', $dateDetails);?>
<?php js::set('module', $app->rawModule);?>
<?php js::set('method', $app->rawMethod);?>
<div id='mainContent' class='main-content load-indicator' data-loading='<?php echo $lang->programplan->exporting;?>'>
  <form class="main-form form-ajax">
    <div class="example">
      <?php echo html::commonButton($lang->programplan->full, 'id="fullScreenBtn"', 'btn btn-primary btn-sm')?>
      <?php if($app->rawModule == 'review' and $app->rawMethod == 'assess') unset($lang->programplan->stageCustom->date); ?>
      <?php echo html::checkbox('stageCustom', $lang->programplan->stageCustom, $selectCustom);?>
      <div class='btn btn-link' id='ganttPris'>
        <strong><?php echo $lang->task->pri . " : "?></strong>
        <?php foreach($lang->execution->gantt->color as $pri => $color):?>
        <span style="background:#<?php echo $color?>"><?php echo $pri;?></span> &nbsp;
        <?php endforeach;?>
      </div>
    </div>
  </form>
  <div id='ganttContainer'>
    <div class='gantt' id='ganttView'></div>
  </div>
  <a id='ganttDownload' download='gantt-export-<?php echo $projectID;?>.png'></a>
</div>
<script>
var scriptLoadedMap   = {};
var loadingPrefixText = '<?php echo $lang->programplan->exporting;?>';
function getRemoteScript(url, successCallback, errorCallback)
{
    if(scriptLoadedMap[url]) return successCallback && successCallback();
    $.getScript(url, function()
    {
        scriptLoadedMap[url] = true;
        if(successCallback) successCallback();
    }).fail(function()
    {
        if(errorCallback) errorCallback('Cannot load "' + url + '".');
    });
}
function updateProgress(progress)
{
    var progressText = loadingPrefixText;
    if(progress < 1) progressText += Math.floor(progress * 100) + '%';
    $('#mainContent').attr('data-loading', progressText);
}
function drawGanttToCanvas(exportType, successCallback, errorCallback)
{
    updateProgress(0);
    exportType          = exportType || 'image';
    var $ganttView      = $('#ganttView');
    var oldHeight       = $ganttView.css('height');
    var $ganttContainer = $('#ganttContainer');
    var $ganttDataArea  = $ganttView.find('.gantt_data_area');
    var $ganttDridData  = $ganttView.find('.gantt_grid_data');

    var ganttHeight = $ganttView.find('.gantt_grid_scale').outerHeight();
    ganttHeight += <?php echo count($plans['data'])?> * 25;

    <?php if($selectCustom == 'task'):?>
    var ganttWidth  = $ganttDridData.width() - 100;
    <?php else:?>
    var ganttWidth = $ganttDataArea.outerWidth() + $ganttDridData.outerWidth();
    <?php endif;?>

    $ganttContainer.css(
    {
        height: ganttHeight + $('#ganttHeader').outerHeight() + 80,
        width: ganttWidth + 93
    });
    $ganttView.css('height', ganttHeight);
    gantt.render();
    updateProgress(0.1);
    getRemoteScript('<?php echo $jsRoot . 'html2canvas/min.js';?>', function()
    {
        updateProgress(0.2);
        var afterFinish = function(canvas)
        {
            $ganttContainer.css(
            {
                width: '',
                height: ''
            });
            $ganttView.css('height', oldHeight);
            if(canvas) canvas.remove();
        };
        var delayTime = Math.max(1000, Math.floor(10 * (ganttHeight * ganttWidth) / 100000));
        var progressTimer;
        if(delayTime > 1500)
        {
            var startProgress = 0.2;
            var deltaProgress = 0.5 / Math.floor(delayTime/1000);
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
            html2canvas($ganttContainer[0], {logging: false}).then(function(canvas)
            {
                var isExportPDF = exportType === 'pdf';
                updateProgress(isExportPDF ? 0.8 : 0.9);
                canvas.onerror = function()
                {
                    afterFinish(canvas);
                    if(errorCallback) errorCallback('Cannot convert image to blob.');
                };
                if(isExportPDF)
                {
                    var width = canvas.width;
                    var height = canvas.height;
                    getRemoteScript('<?php echo $jsRoot . 'pdfjs/min.js';?>', function()
                    {
                        updateProgress(0.8);
                        var pdf = new jsPDF(
                        {
                            format: [width, height],
                            unit: 'px',
                            orientation: width > height ? 'l' : 'p'
                        });
                        pdf.addImage(canvas, 0, 0, pdf.internal.pageSize.getWidth(), pdf.internal.pageSize.getHeight());
                        pdf.save('gantt-export-<?php echo $projectID;?>.pdf');
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
                        var imageUrl = URL.createObjectURL(blob);
                        $('#ganttDownload').attr({href: imageUrl})[0].click();
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

function exportGantt(exportType)
{
    var $mainContent = $('#mainContent');
    $mainContent.addClass('loading').css('height', Math.max(200, Math.floor($(window).height() - $('#footer').outerHeight() - $('#header').outerHeight() - 38)));
    $('#ganttExportDate').text(new Date().format('yyyy-MM-dd hh:mm:ss'));
    var afterFinish = function(url)
    {
        setTimeout(function()
        {
            $mainContent.css('height', '').removeClass('loading');
        }, 300);
        updateProgress(1);
    };
    drawGanttToCanvas(exportType, afterFinish, function(errorText)
    {
        afterFinish();
        $.zui.messager.danger('<?php echo $lang->programplan->exportFail;?>' + (errorText || ''));
    });
}

function getByIdForGantt(list, id)
{
    for (var i = 0; i < list.length; i++)
    {
        if (list[i].key == id) return list[i].label || "";
    }
    return "";
}

function zoomTasks(node)
{
    switch(node.value)
    {
        case "day":
            gantt.config.min_column_width = 70;
            gantt.config.scales = [{unit: 'day', step: 1, format: '%m-%d'}];
            gantt.config.scale_height = 35;
        break;
        case "week":
            gantt.config.min_column_width = 70;
            gantt.config.scales = [{unit: 'week', step: 1, format: "<?php echo $lang->execution->gantt->zooming['week'];?> #%W"}, {unit:"day", step:1, date:"%D"}]
            gantt.config.scale_height = 60;
        break;
        case "month":
            gantt.config.min_column_width = 70;
            gantt.config.scale_height = 60;
            gantt.config.scales = [{unit: 'month', step: 1, format: '%M'}, {unit:"week", step:1, date:"<?php echo $lang->execution->gantt->zooming['week'];?> #%W"}];
        break;
    }
    gantt.render();
}

function updateCriticalPath()
{
    gantt.config.highlight_critical_path = !gantt.config.highlight_critical_path;
    if(gantt.config.highlight_critical_path)
    {
        $('#criticalPath').html(<?php echo json_encode($lang->programplan->hideCriticalPath);?>);
        gantt.config.highlight_critical_path = true;
    }
    else
    {
        $('#criticalPath').html(<?php echo json_encode($lang->programplan->showCriticalPath);?>);
        gantt.config.highlight_critical_path = false;
    }
    gantt.render();
}

$("#fullScreenBtn").on("click", function()
{
    $("#mainContent").css("z-index", "5");
    $("#ganttView").css("z-index", "5");
});

function exitHandler()
{
    if (module == 'review' && method == 'assess' && !document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement)
    {
        location.reload();
    }
}

$(function()
{
    document.addEventListener('fullscreenchange', exitHandler);
    var layout;

    // Set gantt view height
    var resizeGanttView = function()
    {
        if(gantt.getState().fullscreen) return false;
        $('#ganttView').css('height', Math.max(200, Math.floor($(window).height() - $('#footer').outerHeight() - $('#header').outerHeight() - $('#mainMenu').outerHeight() - 80)));
    };

    var ganttData = $.parseJSON(<?php echo json_encode(json_encode($plans));?>);
    if(!ganttData.data) ganttData.data = [];

    gantt.config.readonly          = true;
    gantt.config.row_height        = 25;
    gantt.config.min_column_width  = 40;
    gantt.config.details_on_create = false;
    gantt.config.scales            = [{unit: 'day', step: 1, format: '%m-%d'}];
    gantt.config.duration_unit     = "day";

    gantt.config.columns = [
    {name: 'text',     width: '*', tree: true, resize: true, width:200},
    {name: 'begin',    align: 'center', resize: true, width: 80},
    {name: 'deadline', align: 'center', resize: true, width: 80},
    {name: 'duration', align: 'center', resize: true, width: 60},
    {name: 'percent',  align: 'center', resize: true, width:70, template: function(plan)
        {
            if(plan.percent) return Math.round(plan.percent) + '%';
        }
    },
    {name: 'taskProgress', align: 'center', resize: true, width: 60},
    {name: 'realBegan',  align: 'center', resize: true, width: 80},
    {name: 'realEnd', align: 'center', width: 80}
    ];

    gantt.locale.labels.column_text         = "<?php echo $lang->programplan->name;?>";
    gantt.locale.labels.column_percent      = "<?php echo $lang->programplan->percentAB;?>";
    gantt.locale.labels.column_taskProgress = "<?php echo $lang->programplan->taskProgress;?>";
    gantt.locale.labels.column_begin        = "<?php echo $lang->programplan->begin;?>";
    gantt.locale.labels.column_deadline     = "<?php echo $lang->programplan->end;?>";
    gantt.locale.labels.column_realBegan    = "<?php echo $lang->programplan->realBegan;?>";
    gantt.locale.labels.column_realEnd      = "<?php echo $lang->programplan->realEnd;?>";
    gantt.locale.labels.column_duration     = "<?php echo $lang->programplan->duration;?>";

    if((module == 'review' && method == 'assess') || dateDetails)
    {
        gantt.config.show_chart = false;
    }

    var date2Str  = gantt.date.date_to_str(gantt.config.task_date);
    var today     = new Date();
    var todayTips = "<?php echo $lang->programplan->today;?>";
    gantt.addMarker({
        start_date: today,
        css: "today",
        text: todayTips,
        title: todayTips + ": " + date2Str(today)
    });

    gantt.templates.task_class       = function(start, end, task){return 'pri-' + (task.pri || 0);};
    gantt.templates.scale_cell_class = function(date)
    {
        if(date.getDay() == 0 || date.getDay() == 6) return 'weekend';
    };

    gantt.templates.link_class = function(link)
    {
        var types = gantt.config.links;
        if(link.type == types.finish_to_start)  return 'finish_to_start';
        if(link.type == types.start_to_start)   return 'start_to_start';
        if(link.type == types.finish_to_finish) return 'finish_to_finish';
        if(link.type == types.start_to_finish)  return 'start_to_finish';
    };

    gantt.templates.timeline_cell_class = function(item, date)
    {
        if(date.getDay() == 0 || date.getDay() == 6) return 'weekend';
    };

    gantt.attachEvent('onTemplatesReady', function()
    {
        $('#fullScreenBtn').click(function()
        {
            if(module == 'review' && method == 'assess')
            {
	        	gantt.config.layout = layout;
                gantt.init('ganttView');
            }
            gantt.expand();
        });

    });

    var isGanttExpand    = false;
    var delayTimer       = null;
    var handleFullscreen = function()
    {
        if(isGanttExpand)
        {
            $('body').addClass('gantt-fullscreen');
            $('#ganttView').css('height', $(window).height() - 40);
            isGanttExpand = false;
        }
        else
        {
            $('body').removeClass('gantt-fullscreen');
            resizeGanttView();
        }
        delayTimer = null;
    };
    var delayHandleFullscreen = function()
    {
        if(delayTimer) clearTimeout(delayTimer);
        delayTimer = setTimeout(handleFullscreen, 50);
    };
    gantt.attachEvent('onBeforeExpand', function()
    {
        $('body').addClass('gantt-fullscreen');
        isGanttExpand = true;
        return true;
    });
    if(document.addEventListener)
    {
        document.addEventListener('webkitfullscreenchange', delayHandleFullscreen, false);
        document.addEventListener('mozfullscreenchange', delayHandleFullscreen, false);
        document.addEventListener('fullscreenchange', delayHandleFullscreen, false);
        document.addEventListener('MSFullscreenChange', delayHandleFullscreen, false);
    }

    resizeGanttView();
    $(window).resize(resizeGanttView);

    gantt.templates.grid_folder = function(item) {
        return "";
    };

    gantt.templates.grid_file = function(item) {
        return "";
    };

    gantt.init('ganttView');
    gantt.parse(ganttData);
    gantt.showDate(new Date());

    // Show task in modal on click task
    var taskModalTrigger = new $.zui.ModalTrigger({type: 'iframe', width: '80%'});
    gantt.attachEvent('onTaskClick', function(id, e)
    {
        if($(e.srcElement).hasClass('gantt_close') || $(e.srcElement).hasClass('gantt_open')) return false;

        if(typeof id === 'string') id = parseInt(id);
        if(!isNaN(id) && id > 0)
        {
            //taskModalTrigger.show({url: createLink('task', 'view', 'taskID=' + id, 'html', true)});
        }
    });

    // Make folder can open or close by click
    $('#ganttView').on('click', '.gantt_close,.gantt_open', function()
    {
        var $task = $(this).closest('.gantt_row_task');
        var task  = gantt.getTask($task.attr('task_id'));
        if(task) gantt[task.$open ? 'close' : 'open'](task.id);
    });

    $(".checkbox-primary").on('click', function()
    {
        var stageCustom = [];
        $("input[name='stageCustom[]']:checked").each(function()
        {
            var custom = $(this).val();
            stageCustom.push(custom);
        });

        if(stageCustom.length == 0) stageCustom = 0;
        $.ajax({
            url: customUrl,
            dataType: "json",
            data: {stageCustom: stageCustom},
            type: "post",
            success: function(result)
            {
                window.location.reload();
            }
        });
    });
});
</script>
