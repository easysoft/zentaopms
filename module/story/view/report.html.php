<?php
/**
 * The report view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: report.html.php 1594 2011-03-13 07:27:55Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('product', 'browse', "productID=$productID&browseType=$browseType&moduleID=$moduleID"), "<i class='icon icon-back icon-sm'> </i>" . $lang->goback, '', "class='btn btn-link'");?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='text'><?php echo $lang->story->report->common;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class='main-row'>
  <div class='side-col col-lg'>
    <div class='panel'>
      <div class='panel-heading'>
        <div class='panel-title'><?php echo $lang->story->report->select;?></div>
      </div>
      <div class='panel-body'>
        <form method='post' id='chartTypesForm'>
          <div class='checkboxes'>
            <?php echo html::checkBox('charts', $lang->story->report->charts, $checkedCharts, '', 'block');?>
          </div>
          <div class='btn-toolbar'>
            <?php echo html::selectAll();?>
            <?php echo html::submitButton($lang->story->report->create, '', 'btn btn-primary');?>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class='main-col'>
    <div class='cell'>
      <div class='btn-toolbar'>
        <?php unset($lang->report->typeList['default']);?>
        <?php foreach($lang->report->typeList as $type => $typeName):?>
        <?php echo html::a("javascript:changeChartType(\"$type\")", "<i class='icon icon-chart-{$type} muted'> </i>" . $typeName, '', "class='btn btn-link " . ($type == $chartType ? 'btn-active-line' : '') . "'")?>
        <?php endforeach;?>
      </div>
      <div class='text-muted' style='padding-top:5px'><?php echo str_replace('%tab%', $lang->product->unclosed . $lang->story->common, $lang->report->notice->help);?></div>
      <?php foreach($charts as $chartType => $chartOption):?>
      <div class='table-row chart-row'>
        <div class='main-col'>
          <div class='chart-wrapper text-center'>
            <h4><?php echo $lang->story->report->charts[$chartType];?></h4>
            <div class='chart-canvas'><canvas id='chart-<?php echo $chartType ?>' width='<?php echo $chartOption->width;?>' height='<?php echo $chartOption->height;?>' data-responsive='true'></canvas></div>
          </div>
        </div>
        <div class='side-col col-xl'>
          <div style="overflow:auto;" class='table-wrapper'>
            <table class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='<?php echo $chartOption->type; ?>' data-target='#chart-<?php echo $chartType ?>' data-animation='false'>
              <thead>
                <tr>
                  <th class='chart-label' colspan='2'><?php echo $lang->story->report->$chartType->item;?></th>
                  <th class='w-50px text-right'><?php echo $lang->story->report->value;?></th>
                  <th class='w-50px'><?php echo $lang->report->percent;?></th>
                </tr>
              </thead>
              <?php foreach($datas[$chartType] as $key => $data):?>
              <tr>
                <td class='chart-color'><i class='chart-color-dot'></i></td>
                <td class='chart-label text-left'><?php echo $data->name;?></td>
                <td class='chart-value text-right'><?php echo $data->value;?></td>
                <td class='text-right'><?php echo ($data->percent * 100) . '%';?></td>
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
<?php js::set('storyType', $storyType);?>
<?php include '../../common/view/footer.html.php';?>
