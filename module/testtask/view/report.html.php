<?php
/**
 * The report view file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: report.html.php 4657 2013-04-17 02:01:26Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php common::printBack(inlink('cases', "taskID=$taskID&browseType=$browseType"), 'btn btn-link');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='text'><?php echo $lang->testtask->report->common;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class='main-row'>
  <div class='side-col col-lg'>
    <div class='panel'>
      <div class='panel-heading'>
        <div class='panel-title'><?php echo $lang->testtask->report->select;?></div>
      </div>
      <div class='panel-body'>
        <form method='post' id='chartTypesForm'>
          <div class='checkboxes'>
            <?php echo html::checkBox('charts', $lang->testtask->report->charts, $checkedCharts, '', 'block');?>
          </div>
          <div class='btn-toolbar'>
            <?php echo html::selectAll()?>
            <?php echo html::submitButton($lang->testtask->report->create, '', "btn btn-primary");?>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class='main-col'>
    <div class='cell'>
      <div class='btn-toolbar'>
        <?php foreach($lang->report->typeList as $type => $typeName):?>
        <?php echo html::a("javascript:changeChartType(\"$type\")", "<i class='icon icon-chart-{$type}'> </i>" . $typeName, '', "class='btn btn-link " . ($type == $chartType ? 'btn-active-line' : '') . "'")?>
        <?php endforeach;?>
        <div class='pull-right with-padding text-muted'><?php echo $lang->report->notice->help;?></div>
      </div>
      <?php foreach($charts as $chartType => $chartOption):?>
      <div class='table-row chart-row'>
        <div class='main-col'>
          <div class='chart-wrapper text-center'>
            <h4><?php echo $lang->testtask->report->charts[$chartType];?></h4>
            <div class='chart-canvas'><canvas id='chart-<?php echo $chartType ?>' width='<?php echo $chartOption->width;?>' height='<?php echo $chartOption->height;?>' data-responsive='true'></canvas></div>
          </div>
        </div>
        <div class='side-col col-xl'>
          <div style="overflow:auto;" class='table-wrapper'>
            <table class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='<?php echo $chartOption->type; ?>' data-target='#chart-<?php echo $chartType ?>' data-animation='false'>
              <thead>
                <tr>
                  <th class='chart-label' colspan='2'><?php echo $lang->report->item;?></th>
                  <th class='w-50px'><?php echo $lang->report->value;?></th>
                  <th class='w-50px'><?php echo $lang->report->percent;?></th>
                </tr>
              </thead>
              <?php foreach($datas[$chartType] as $key => $data):?>
              <tr class='text-center'>
                <td class='chart-color'><i class='chart-color-dot'></i></td>
                <td class='chart-label'><?php echo $data->name;?></td>
                <td class='chart-value'><?php echo $data->value;?></td>
                <td><?php echo ($data->percent * 100) . '%';?></td>
              </tr>
              <?php endforeach;?>
            </table>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
<?php js::set('productID', $productID);?>
<?php js::set('browseType', $browseType);?>
<?php js::set('branchID', $branchID);?>
<?php js::set('moduleID', $moduleID);?>
<?php js::set('taskID', $taskID);?>
<?php include '../../common/view/footer.html.php';?>
