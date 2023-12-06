<?php
/**
 * The report view file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: report.html.php 4657 2013-04-17 02:01:26Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::backButton('<i class="icon-goback icon-back"></i>  ' . $lang->goback, "data-app='{$app->tab}'", 'btn btn-link');?>
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
        <form method='post' id='chartTypesForm' class='no-stash'>
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
        <?php echo html::a("javascript:changeChartType(\"$type\")", ($type == 'default' ? "<i class='icon icon-list-alt muted'></i> " : "<i class='icon icon-chart-{$type}'></i>") . $typeName, '', "class='btn btn-link " . ($type == $chartType ? 'btn-active-line' : '') . "'")?>
        <?php endforeach;?>
      </div>
      <?php $this->app->loadLang('testcase');?>
      <div class='text-muted' style='padding-top:5px'><?php echo str_replace('%tab%', $lang->testtask->wait . $lang->testcase->common, $lang->report->notice->help);?></div>
      <?php foreach($charts as $chartType => $chartOption):?>
      <div class='table-row chart-row'>
        <div class='main-col'>
          <div class='chart-wrapper text-center'>
            <h4>
            <?php echo $lang->testtask->report->charts[$chartType];?>
            <?php if($chartType  == 'testTaskPerRunResult'):?>
              <?php
              $total = 0;
              foreach($datas['testTaskPerRunResult'] as $key => $data) $total += $data->value;
              $pass   = isset($datas['testTaskPerRunResult']['pass']) ? $datas['testTaskPerRunResult']['pass']->value : 0;
              $noExec = isset($datas['testTaskPerRunResult']['']) ? $datas['testTaskPerRunResult']['']->value : 0;
              $fail   = isset($datas['testTaskPerRunResult']['fail']) ? $datas['testTaskPerRunResult']['fail']->value : 0;
              ?>
              <a data-toggle='tooltip' title='<?php echo sprintf($lang->testtask->report->testTaskPerRunResultTip, $total, $pass, $noExec, $fail);?>'><i class='icon-help'></i></a>
            <?php endif;?>
            </h4>
            <div class='chart-canvas'><canvas id='chart-<?php echo $chartType ?>' width='<?php echo $chartOption->width;?>' height='<?php echo $chartOption->height;?>' data-responsive='true'></canvas></div>
          </div>
        </div>
        <div class='side-col col-xl'>
          <div style="overflow:auto;" class='table-wrapper'>
            <table class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='<?php echo $chartOption->type; ?>' data-target='#chart-<?php echo $chartType ?>' data-animation='false'>
              <thead>
                <tr>
                  <th class='chart-label' colspan='2'><?php echo $lang->report->item;?></th>
                  <th class='w-50px text-right'><?php echo $lang->report->value;?></th>
                  <th class='w-60px'><?php echo $lang->report->percent;?></th>
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
<?php js::set('taskID', $taskID);?>
<?php include '../../common/view/footer.html.php';?>
