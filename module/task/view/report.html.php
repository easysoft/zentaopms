<?php
/**
 * The report view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      wenjie<wenjie@cnezsoft.com>
 * @package     execution
 * @version     $Id: report.html.php 1594 2011-04-10 11:00:00Z wj $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::backButton($lang->goback, '', 'btn');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='text'><?php echo $lang->task->report->common;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class='main-row'>
  <div class='side-col col-lg'>
    <div class='panel'>
      <div class='panel-heading'>
        <div class='panel-title'><?php echo $lang->task->report->select;?></div>
      </div>
      <div class='panel-body'>
        <form method='post' id='chartTypesForm' class='no-stash'>
          <div class='checkboxes'>
            <?php echo html::checkBox('charts', $lang->task->report->charts, $checkedCharts, '', 'block')?>
          </div>
          <div class='btn-toolbar'>
            <?php echo html::selectAll();?>
            <?php echo html::submitButton($lang->task->report->create, "", 'btn btn-primary');?>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class='main-col'>
    <div class='cell'>
      <div class='btn-toolbar'>
        <?php foreach($lang->report->typeList as $type => $typeName):?>
        <?php echo html::a("javascript:changeChartType(\"$type\")", ($type == 'default' ? "<i class='icon icon-list-alt muted'></i> " : "<i class='icon icon-chart-{$type} muted'></i> ") . $typeName, '', "class='btn btn-link " . ($type == $chartType ? 'btn-active-line' : '') . "'")?>
        <?php endforeach;?>
      </div>
      <div class='text-muted' style='padding-top:5px'><?php echo str_replace('%tab%', $lang->execution->unclosed . $lang->task->common, $lang->report->notice->help);?></div>
      <?php foreach($charts as $chartType => $chartOption):?>
      <div class='table-row chart-row'>
        <div class='main-col'>
          <div class='chart-wrapper text-center'>
            <h4><?php echo $lang->task->report->charts[$chartType];?></h4>
            <div class='chart-canvas'><canvas id='chart-<?php echo $chartType ?>' width='<?php echo $chartOption->width;?>' height='<?php echo $chartOption->height;?>' data-responsive='true'></canvas></div>
          </div>
        </div>
        <div class='side-col col-xl'>
          <div style="overflow:auto;" class='table-wrapper'>
            <table class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='<?php echo $chartOption->type; ?>' data-target='#chart-<?php echo $chartType ?>' data-animation='false'>
              <thead>
                <tr>
                  <th class='chart-label' colspan='2'><?php echo $lang->task->report->$chartType->item;?></th>
                  <th class='w-60px text-right'><?php echo $lang->task->report->value;?></th>
                  <th class='w-60px text-right'><?php echo $lang->report->percent;?></th>
                </tr>
              </thead>
              <tbody>
              <?php foreach($datas[$chartType] as $key => $data):?>
              <tr>
                <td class='chart-color w-20px'><i class='chart-color-dot'></i></td>
                <td class='chart-label text-left'><?php echo $data->name;?></td>
                <td class='chart-value text-right'><?php echo $data->value;?></td>
                <?php $percent = $data->percent * 100;?>
                <?php $percent = sprintf("%.2f", $percent);?>
                <td class='text-right'><?php echo $percent . '%';?></td>
              </tr>
              <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
<?php js::set('executionID', $executionID);?>
<?php js::set('browseType', $browseType);?>
<?php include '../../common/view/footer.html.php';?>
