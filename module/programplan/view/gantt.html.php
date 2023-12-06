<?php
/**
 * The gantt of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: gantt.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php chdir(__DIR__);?>
<?php include '../../common/view/gantt.html.php';?>
<?php if(isset($project) and $project->model == 'ipd') js::set('reviewPoints', json_encode($reviewPoints));?>
<?php js::set('projectID', $projectID);?>
<style>
.submitBtn{display:none}
.gantt_row_task:hover .submitBtn{ display: inline-block;}
.editDeadline {display: none; line-height: normal !important; padding: 2px 6px; color: #fff !important;}
td:hover > .editDeadline {display: inline-block !important;}
.gantt_tree_content table {width: 100%;}
.gantt_tree_content table td {padding: 0 !important;}
#ganttView {height: 600px;}
#mainContent:before {background: #fff;}
.checkbox-primary {margin-top: 0px; margin-left: 10px;}
form {display: block; margin-top: 0em; margin-block-end: 1em;}
.gantt_task_content span.task-label, .gantt_task_content span.label-pri{display: none;}
.gantt_task_content .icon-seal{display: none;}
#ganttPris > span {display: inline-block; line-height: 20px; min-width: 20px; border-radius: 2px;}
.gantt_task_line.gantt_selected {box-shadow: 0 1px 1px rgba(0,0,0,.05), 0 2px 6px 0 rgba(0,0,0,.045)}
.gantt_link_arrow_right {border-left-color: #2196F3;}
.gantt_link_arrow_left {border-right-color: #2196F3;}
.icon-confirm {color: #18a6fd; font-size: 18px;}
.icon-seal{font-size: 18px;}
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
.gantt_grid_head_owner_id {text-align: left}
.gantt_critical_task{background:#e63030 !important; border-color:#9d3a3a !important;}
.gantt_marker .gantt_marker_content {left: -15px; background-color: #f51e1e;}
.gantt_row{cursor: pointer;}

#ganttDownload, #ganttHeader {display: none;}
#ganttContainer {margin-top: 40px;}
#mainContent {padding: 10px 20px 20px 20px;}
#mainContent:before {background: #fff;}
#mainContent.loading {overflow: hidden}
#mainContent.loading #ganttView {overflow: hidden}
#mainContent.loading #ganttHeader {display: block; padding-bottom: 20px; margin: 0; height: 46px;}
#mainContent.loading #ganttDownload {display: inline}
#mainContent.loading #ganttContainer {padding: 40px;}
#mainContent.loading .scrollVer_cell,
#mainContent.loading .scrollVer_cell {display: none;}
.gantt-fullscreen #header,
.gantt-fullscreen #mainMenu,
.gantt-fullscreen #footer {display: none!important;}
.gantt-fullscreen #mainContent {position: fixed; top: 0; right: 0; bottom: 0; left: 0}
.gantt_grid_head_cell.gantt_grid_head_text{overflow:visible;}
.gantt_grid_head_cell, .gantt_scale_cell{color:#000000!important;}
.gantt_tree_content{color:#838A9D;}
.gantt_row > div:first-child .gantt_tree_content{color:#3C4353;}
.gantt_task_line.gantt_task_inline_color{border:0px;}
.gantt_grid_scale, .gantt_task_scale, .gantt_task_vscroll{background-color: #F2F7FF;}
#myCover {display:none;left:12px!important;z-index:10!important;top:9px!important;height:unset!important;}
.button-group{position: relative;}
.flax{display: flex; margin-bottom: 10px;}
.switchBtn > i {padding-left: 7px;}
#mainContent > .pull-left > .btn-group > .text{display: block;margin-top: 7px;}
#mainContent > .pull-left > .btn-group > a > .text{overflow: hidden;display: block;}
#mainContent > .pull-right > .button-group  .text{margin-left: 0px;}
.pull-right .icon-plus.icon-sm:before{vertical-align: 4%;}
#ganttView .gantt_resizer{min-width: unset !important;}
</style>
<?php js::set('customUrl', $this->createLink('programplan', 'ajaxCustom'));?>
<?php js::set('dateDetails', $dateDetails);?>
<?php js::set('module', $app->rawModule);?>
<?php js::set('method', $app->rawMethod);?>
<?php js::set('ganttType', $ganttType);?>
<?php js::set('showFields', $this->config->programplan->ganttCustom->ganttFields);?>
<?php js::set('canGanttEdit', common::hasPriv('programplan', 'ganttEdit'));?>
<?php js::set('zooming', isset($zooming) ? $zooming : 'day');?>
<?php js::set('canEditDeadline', common::hasPriv('review', 'edit'));?>
<div id='mainContent' class='main-content load-indicator' data-loading='<?php echo $lang->programplan->exporting;?>'>
  <?php if($this->app->getModuleName() == 'programplan'):?>
  <div class='btn-toolbar pull-left'>
    <div class='btn-group'>
      <?php if(!empty($project->division)):?>
      <?php $viewName = $productID != 0 ? zget($productList,$productID) : $lang->product->allProduct;?>
      <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis' data-toggle='dropdown' style="max-width: 120px;"><span class='text' title='<?php echo $viewName;?>'><?php echo $viewName;?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
        <?php
          $class = '';
          if($productID == 0) $class = 'class="active"';
          foreach($productList as $key => $productName)
          {
              $class = $productID == $key ? 'class="active"' : '';
              echo "<li $class>" . html::a($this->createLink('programplan', 'browse', "projectID=$projectID&productID=$key&type=gantt"), $productName, '', "title='{$productName}' class='text-ellipsis'") . "</li>";
          }
        ?>
      </ul>
      <?php else:?>
      <?php echo "<span class='text'>{$lang->programplan->gantt}</span>";?>
      <?php endif;?>
    </div>
  </div>
  <div class="pull-right btn-toolbar flax">
    <div class="btn-group">
      <?php echo html::a('',"<i class='icon-gantt-alt'></i> &nbsp;", '', "class='btn btn-icon text-primary switchBtn' title='{$lang->programplan->gantt}'");?>
      <?php echo html::a($this->createLink('project', 'execution', "status=all&projectID=$projectID"),"<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon switchBtn' title='{$lang->project->bylist}'");?>
    </div>
    <a href='javascript:fullScreen();' id='fullScreenBtn' class='btn btn-link'><i class='icon icon-fullscreen'></i> <?php echo $lang->programplan->full;?></a>
    <div class='button-group'>
    <button class='btn btn-link' data-toggle='dropdown'><i class='icon icon-export'></i> <span class='text'><?php echo $this->lang->export;?></span> <span class='caret'></span></button>
      <ul class='dropdown-menu' id='exportActionMenu'>
      <li><a href='javascript:exportGantt()'><?php echo $lang->execution->gantt->exportImg;?></a></li>
      <li><a href="javascript:exportGantt('pdf')"><?php echo $lang->execution->gantt->exportPDF;?></a></li>
      </ul>
    </div>
    <?php
    echo html::a(helper::createLink('programplan', 'ajaxcustom', '', '', true), '<i class="icon icon-cog-outline"></i> ' . $lang->settings, '', "class='iframe btn btn-link' data-width='45%'");
    if(common::hasPriv('programplan', 'create') and empty($product->deleted)) echo html::a($this->createLink('programplan', 'create', "projectID=$projectID"), "<i class='icon icon-sm icon-plus'>    </i> " . $this->lang->programplan->create, '', "class='btn btn-primary'");
    ?>
  </div>
  <?php endif;?>
  <div id='ganttContainer'>
    <div class='gantt' id='ganttView'></div>
  </div>
  <?php $fileName = "gantt-export-{$projectID}";?>
  <a id='ganttDownload' target='hiddenwin' download='<?php echo "{$fileName}.png";?>'></a>
  <?php
  $typeHtml  = '<div class="btn-group">';
  $typeHtml .= '<button class="btn btn-link" data-toggle="dropdown"><span class="text">' . $lang->programplan->ganttBrowseType[$ganttType] . '</span> <span class="caret"></span></button>';
  $typeHtml .= '<ul class="dropdown-menu">';
  foreach($lang->programplan->ganttBrowseType as $browseType => $typeName)
  {

      if($app->rawModule == 'review' and $app->rawMethod == 'assess')
      {
        $typeHtml .= '<li ' . ($ganttType == $browseType ? "class='active'" : '') . '>' . html::a($this->createLink('review', 'assess', "reivewID=$reviewID&from=&type=$browseType"), $typeName) . '</li>';
      }
      else
      {
        $typeHtml .= '<li ' . ($ganttType == $browseType ? "class='active'" : '') . '>' . html::a($this->createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=$browseType"), $typeName) . '</li>';
      }
  }
  $typeHtml .= '</ul></div>';
  ?>
</div>
<div id="myCover">
  <div class="gantt_control">
    <div class='btn btn-link' id='ganttPris'>
      <strong><?php echo $lang->task->pri . " : "?></strong>
      <?php foreach($lang->execution->gantt->progressColor as $pri => $color):?>
      <?php if($pri <= 4):?>
      <span style="background:<?php echo $color?>"><?php echo $pri;?></span> &nbsp;
      <?php endif;?>
      <?php endforeach;?>
    </div>
  </div>
  <div id="gantt_here"></div>
</div>
<script>
var scriptLoadedMap   = {};
var loadingPrefixText = '<?php echo $lang->programplan->exporting;?>';

//After that you have to redefine the getFullscreenElement() method to return a custom root node that will be expanded to full screen:
gantt.ext.fullscreen.getFullscreenElement = function() {
    return document.getElementById("myCover");
}
gantt.init("gantt_here");

// before gantt is expanded to full screen
gantt.attachEvent("onBeforeExpand",function(){
    $('#myCover').css('display', 'unset');
    $('#mainContent .pull-right').css('opacity', '0');
    $('.btn-toolbar').css('display', 'none');
    return true;
});

// when gantt exited the full screen mode
gantt.attachEvent("onCollapse", function (){
    $('#myCover').css('display', 'none');
    $('#mainContent .pull-right').css('opacity', '1');
    $('.btn-toolbar').css('display', 'flex');
});

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
    $.getScript(url, function()
    {
        scriptLoadedMap[url] = true;
        if(successCallback) successCallback();
    }).fail(function()
    {
        if(errorCallback) errorCallback('Cannot load "' + url + '".');
    });
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
    var progressText = loadingPrefixText;
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
    var $ganttView      = $('#ganttView');
    var oldHeight       = $ganttView.css('height');
    var $ganttContainer = $('#ganttContainer');
    var $ganttDataArea  = $ganttView.find('.gantt_data_area');
    var $ganttDridData  = $ganttView.find('.gantt_grid_data');

    var ganttRowHeight = $ganttView.find('.gantt_row').first().outerHeight() || 25;
    var ganttHeight = $ganttView.find('.gantt_grid_scale').outerHeight() + <?php echo count($plans['data'])?> * ganttRowHeight;
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
            if(canvas)
            {
                try
                {
                    canvas.removeNode(true)
                }
                catch(err)
                {
                    canvas.remove()
                };
            }
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
                        pdf.save('<?php echo "$fileName.pdf"?>');
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
                        if(navigator.msSaveBlob)
                        {
                            navigator.msSaveOrOpenBlob(blob, '<?php echo $fileName; ?>.png');
                        }
                        else
                        {
                            $('#ganttDownload').attr({href: imageUrl})[0].click();
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
function exportGantt(exportType)
{
    var $mainContent = $('#mainContent');
    $mainContent.addClass('loading').css('height', Math.max(200, Math.floor($(window).height() - $('#footer').outerHeight() - $('#header').outerHeight() - $('#mainMenu').outerHeight() - 38)));
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
    for (var i = 0; i < list.length; i++)
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
            gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'week', step: 1, format: "<?php echo $lang->execution->gantt->zooming['week'];?> #%W"}, {unit:"day", step:1, date:"%D"}]
            gantt.config.scale_height = 22 * gantt.config.scales.length;
        break;
        case "month":
            gantt.config.min_column_width = 70;
            gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'month', step: 1, format: '%M'}, {unit:"week", step:1, date:"<?php echo $lang->execution->gantt->zooming['week'];?> #%W"}];
            gantt.config.scale_height = 22 * gantt.config.scales.length;
        break;
    }

    gantt.render();
    $('.gantt_grid_head_cell .sort').addClass(value);
}

window.onload = function () {
    zoomTasks(zooming);
}

/**
 * Update criticalPath
 *
 * @access public
 * @return void
 */
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

/* Validate task drag. */
function validateResources(id)
{
    var task = gantt.getTask(id);
    var from = new Date(task.start_date),
        to   = new Date(task.end_date);
    var status = task.status;
    var type   = task.type;
    var statusLang = "<?php echo $lang->task->statusList['wait'];?>";
    flag = true;

    /* Check status. */
    if(status !== statusLang && type != 'point')
    {
        if(type == 'task')
        {
            tipMsg = "<?php echo $lang->programplan->error->taskDrag;?>";
        }
        else
        {
            tipMsg = "<?php echo $lang->programplan->error->planDrag;?>";
        }
        new $.zui.Messager(tipMsg.replace('%s', status), {
            type: 'danger',
            icon: 'exclamation-sign'
        }).show();
        gantt.refreshData();
        return false;
    }

    task.begin    = from;
    task.deadline = (new Date(to.valueOf() - 1));;

    gantt.updateTask(task.id);
    var itemID;
    if(type == 'task')
    {
        itemID = task.id.split("-")[1];
    }
    else if(type == 'plan')
    {
        itemID = task.id;
    }
    else if(type == 'point')
    {
        itemID = task.id.split("-")[2];
    }

    /* Check data. */
    var postData = {
        'id'        : itemID,
        'startDate' : from.toLocaleDateString('en-CA'),
        'endDate'   : to.toLocaleDateString('en-CA'),
        'type'      : type
    };
    var link = createLink('programplan', 'ajaxResponseGanttDragEvent');
    /* Sync Close. */
    $.ajax({
        url: link,
        dataType: "json",
        async: false,
        data: postData,
        type: "post",
        success: function(response)
        {
            if(response.result == 'fail' && response.message)
            {
                new $.zui.Messager(response.message, {
                    type: 'danger',
                    icon: 'exclamation-sign'
                }).show();
                flag = false;
            }
        }
    });

    return flag;
}

$(function()
{
    document.addEventListener('fullscreenchange', exitHandler);
    var layout;

    // Set gantt view height
    var resizeGanttView = function()
    {
        if(gantt.getState().fullscreen) return false;
        $('#ganttView').css('height', Math.max(200, Math.floor($(window).height() - $('#footer').outerHeight() - $('#header').outerHeight() - $('#mainMenu').outerHeight() - 120)));
    };

    var ganttData = $.parseJSON(<?php echo json_encode(json_encode($plans));?>);
    if(!ganttData.data) ganttData.data = [];

    <?php
    $userList = array();
    if(!empty($users))
    {
        foreach($users as $account => $realname)
        {
            $user = array();
            $user['key']   = $account;
            $user['label'] = $realname;
            $userList[]    = $user;
        }
    }
    ?>
    gantt.serverList("userList", <?php echo json_encode($userList);?>);

    gantt.config.readonly            = canGanttEdit ? false : true;
    gantt.config.details_on_dblclick = false;
    gantt.config.order_branch        = ganttType == 'assignedTo' ? false : true;
    gantt.config.drag_progress       = false;
    gantt.config.drag_links          = false;
    gantt.config.drag_move           = ganttType == 'assignedTo' ? false : true;
    gantt.config.drag_resize         = ganttType == 'assignedTo' ? false : true;
    gantt.config.smart_rendering     = true;
    gantt.config.smart_scales        = true;
    gantt.config.static_background   = true;
    gantt.config.show_task_cells     = false;
    gantt.config.row_height          = 32;
    gantt.config.min_column_width    = 40;
    gantt.config.details_on_create   = false;
    gantt.config.scales              = [{unit: "year", step: 1, format: "%Y"}, {unit: 'day', step: 1, format: '%m-%d'}];
    gantt.config.scale_height        = 18 * gantt.config.scales.length;
    gantt.config.duration_unit       = "day";

    gantt.config.columns = [];
    gantt.config.columns.push({name: 'text', width: '*', tree: true, resize: true, min_width:120, width:200});
    gantt.config.columns.push({name:"custom", label:"", align: "left", width: 40, template: getSubmitBtn});
    if(showFields.indexOf('PM') != -1) gantt.config.columns.push({name: 'owner_id', align: 'left', resize: true, width: 80, template: function(task){return getByIdForGantt(gantt.serverList('userList'), task.owner_id)}})
    if(showFields.indexOf('status') != -1) gantt.config.columns.push({name: 'status', align: 'center', resize: true, width: 80});
    gantt.config.columns.push({name: 'begin', align: 'center', resize: true, width: 80});
    if(showFields.indexOf('deadline') != -1) gantt.config.columns.push({name: 'deadline', align: 'center', resize: true, width: 140, template: getDeadlineBtn});
    gantt.config.columns.push({name: 'duration', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('estimate') != -1) gantt.config.columns.push({name: 'estimate', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('progress') != -1) gantt.config.columns.push({name: 'percent', align: 'center', resize: true, width:70, template: function(plan){ if(plan.percent) return Math.round(plan.percent) + '%';}});
    if(showFields.indexOf('taskProgress') != -1) gantt.config.columns.push({name: 'taskProgress', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('realBegan') != -1) gantt.config.columns.push({name: 'realBegan', align: 'center', resize: true, width: 80});
    if(showFields.indexOf('realEnd') != -1) gantt.config.columns.push({name: 'realEnd', align: 'center', resize: true, width: 80});
    if(showFields.indexOf('consumed') != -1) gantt.config.columns.push({name: 'consumed', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('delay') != -1) gantt.config.columns.push({name: 'delay', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('delayDays') != -1) gantt.config.columns.push({name: 'delayDays', align: 'center', resize: false, width: 60});

    function getDeadlineBtn(task)
    {
        var date = task.deadline;
        if(task.type == 'point' && canEditDeadline && (!task.rawStatus || task.rawStatus == 'fail' || task.rawStatus == 'draft')) return "<table><tr><td><span class='deadline'>" + gridDateToStr(new Date(date.valueOf())) + '</span> <a class="btn btn-primary editDeadline" title="<?php echo $lang->programplan->edit;?>"><i class="icon-common-edit icon-edit"></i> <?php echo $lang->programplan->edit;?></a></td></tr></table>';
        return date;
    }

    function getSubmitBtn(task)
    {
        if(task.type == 'point' && !task.rawStatus) return '<button class="btn btn-link submitBtn" title="<?php echo $lang->programplan->submit;?>"><i class="icon-confirm"></i></button>';
    }

    gantt.templates.task_end_date = function(data)
    {
        return gantt.templates.task_date(new Date(date.valueOf() - 1));
    }

    var gridDateToStr = gantt.date.date_to_str("%Y-%m-%d");
    gantt.templates.grid_date_format = function(date, column)
    {
        if(column === "end_date")
        {
            return gridDateToStr(new Date(date.valueOf() - 1));
        }
        else
        {
            return gridDateToStr(date);
        }
    }

    endField = gantt.config.columns.pop();
    endField.resize = false;
    gantt.config.columns.push(endField);

    gantt.locale.labels.column_text         = <?php echo json_encode($typeHtml);?>;
    gantt.locale.labels.column_owner_id     = "<?php echo $lang->programplan->PMAB;?>";
    gantt.locale.labels.column_status       = "<?php echo $lang->statusAB;?>";
    gantt.locale.labels.column_percent      = "<?php echo $lang->programplan->percentAB;?>";
    gantt.locale.labels.column_taskProgress = "<?php echo $lang->programplan->taskProgress;?>";
    gantt.locale.labels.column_begin        = "<?php echo $lang->programplan->begin;?>";
    gantt.locale.labels.column_start_date   = "<?php echo $lang->programplan->begin;?>";
    gantt.locale.labels.column_deadline     = "<?php echo $lang->programplan->end;?>";
    gantt.locale.labels.column_end_date     = "<?php echo $lang->programplan->end;?>";
    gantt.locale.labels.column_realBegan    = "<?php echo $lang->programplan->realBegan;?>";
    gantt.locale.labels.column_realEnd      = "<?php echo $lang->programplan->realEnd;?>";
    gantt.locale.labels.column_duration     = "<?php echo $lang->programplan->duration;?>";
    gantt.locale.labels.column_estimate     = "<?php echo $lang->programplan->estimate;?>";
    gantt.locale.labels.column_consumed     = "<?php echo $lang->programplan->consumed;?>";
    gantt.locale.labels.column_delay        = "<?php echo $lang->programplan->delay;?>";
    gantt.locale.labels.column_delayDays    = "<?php echo $lang->programplan->delayDays;?>";

    if((module == 'review' && method == 'assess') || dateDetails) gantt.config.show_chart = false;

    var date2Str  = gantt.date.date_to_str(gantt.config.task_date);
    var today     = new Date();
    var todayTips = "<?php echo $lang->programplan->today;?>";
    gantt.addMarker({
        start_date: today,
        css: "today",
        text: todayTips,
        title: todayTips + ": " + date2Str(today)
    });

    gantt.templates.task_class     = function(start, end, task){return 'pri-' + (task.pri || 0);};
    gantt.templates.rightside_text = function(start, end, task)
    {
        if(typeof task.owner_id == 'undefined') return;
        if(task.type == 'point') return "<span class='status-" + task.rawStatus + "'>" + task.status + '</span>';
        return getByIdForGantt(gantt.serverList('userList'), task.owner_id);
    };

    gantt.templates.link_class = function(link)
    {
        var types = gantt.config.links;
        if(link.type == types.finish_to_start)  return 'finish_to_start';
        if(link.type == types.start_to_start)   return 'start_to_start';
        if(link.type == types.finish_to_finish) return 'finish_to_finish';
        if(link.type == types.start_to_finish)  return 'start_to_finish';
    };

    gantt.templates.tooltip_text = function (start, end, task)
    {
        if(task.type != 'point') return task.text;
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

    gantt.attachEvent("onBeforeTaskMove", function(id, mode, e)
    {
        task = gantt.getTask(id);
        if(task.type == 'point') return false;
    });

    var isGanttExpand    = false;
    var delayTimer       = null;
    var handleFullscreen = function()
    {
        if(isGanttExpand)
        {
            $('body').addClass('gantt-fullscreen');
            $('#ganttView').css('height', $(window).height() - 60);
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
    var taskModalTrigger = new $.zui.ModalTrigger({type: 'iframe', width: '95%'});
    gantt.attachEvent('onTaskClick', function(id, e)
    {
        var editBtn = $(e.srcElement);
        if(editBtn.hasClass('icon-common-edit'))
        {
           editBtn = editBtn.parent();
        }
        if(editBtn.hasClass('editDeadline'))
        {
           var parentID     = id.split("-")[0];
           var stageEndDate = $("div[data-task-id='" + parentID + "']").find('div[data-column-name="end_date"] > .gantt_tree_content').text();
           var reviewID     = id;
           var deadlineDate = editBtn.prev().text();

           editBtn.datetimepicker(
           {
               weekStart: 1,
               todayBtn:  1,
               autoclose: 1,
               todayHighlight: 1,
               startView: 2,
               minView: 2,
               forceParse: 0,
               format: "yyyy-mm-dd",
               startDate: new Date(),
               endDate: new Date(stageEndDate),
               initialDate: new Date(deadlineDate),
           })
           .on('changeDate', function(ev){
               var year  = ev.date.getFullYear();
               var month = ev.date.getMonth() + 1;
               var day   = ev.date.getUTCDate();
               var formattedDate = year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;

               $.post(createLink('review', 'ajaxChangeTRDeadline'), {'deadline' : formattedDate, 'id' : reviewID , 'projectID' : projectID}, function()
               {
                  location.reload();
               });

           })
           .on('show', function(){
               editBtn.css('display', 'inline-block');
           })
           .on('hide', function(){
               editBtn.css('display', 'none');
           });
           editBtn.datetimepicker('show');
           return false;
        }

        if(editBtn.hasClass('gantt_close') || editBtn.hasClass('gantt_open')) return false;

        var task = gantt.getTask(id);
        if(task.type == 'point' && task.rawStatus) location.href = createLink('review', 'view', 'reviewID=' + task.reviewID);
        if(task.type == 'plan' && !task.isParent)  window.parent.$.apps.open(createLink('execution', 'task', 'id=' + task.id), 'execution');

        if(editBtn.hasClass('icon-confirm'))
        {
            var pointAttr = JSON.parse(reviewPoints);
            var category  = id.split("-")[1];

            if(pointAttr[category]['disabled'])
            {
                new $.zui.Messager(pointAttr[category]['message'], {
                    type: 'danger',
                    icon: 'exclamation-sign'
                }).show();
                return false;
            }
            else
            {
                location.href = createLink('review', 'create', 'projectID=' + projectID);
            }
        }

        if(ganttType == 'assignedTo')
        {
            taskID = id;
        }
        else
        {
            /* The id of task item is like executionID-taskID. e.g. 1507-37829, 37829 is task id. */
            var position = id.indexOf('-');
            if(position < 0) return;
            var taskID = parseInt(id.substring(position + 1));
        }

        if(!isNaN(taskID) && taskID > 0) taskModalTrigger.show({url: createLink('task', 'view', 'taskID=' + taskID, 'html', true)});
    });

    gantt.attachEvent("onBeforeRowDragEnd", function(id, parent, tindex)
    {
        var tasks = gantt.getChildren(parent);
        var task  = gantt.getTask(id);
        var link  = createLink('programplan', 'ajaxResponseGanttMoveEvent');

        //prevent moving to another position.
        if(task.parent != parent || id.indexOf('-') == -1)
        {
            return false;
        }
        else
        {
            $.ajax({
            url: link,
                dataType: "json",
                data: {id: id, tasks: tasks},
                type: "post",
                success: function(result){}
            });
        }

        return true;
    });


    // Make folder can open or close by click
    $('#ganttView').on('click', '.gantt_close,.gantt_open', function()
    {
        var $task = $(this).closest('.gantt_row_task');
        var task  = gantt.getTask($task.attr('task_id'));
        if(task) gantt[task.$open ? 'close' : 'open'](task.id);
    });

    $(".example .checkbox-primary").on('click', function()
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

    $('#ganttContainer').mouseleave(function()
    {
        setTimeout(function(){$('.gantt_tooltip').remove()}, 100);
    });

    /* Link attachEvent onAfterTaskDrag */
    gantt.attachEvent("onBeforeTaskChanged", function(id, mode, task)
    {
        return validateResources(id);
    });

    gantt.attachEvent("onRowDragStart", function(id, target, e) {
        //any custom logic here
        var task = gantt.getTask(id);
        if(task.type != 'task') return false;
        return true;
    });
});
</script>
