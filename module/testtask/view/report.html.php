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
<?php include '../../common/view/chart.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['testtask']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['report']);?></small> <?php echo $lang->testtask->report->common;?></strong>
  </div>
  <div class='actions'>
    <?php echo html::a($this->createLink('testtask', 'cases', "taskID=$taskID&browseType=$browseType"), $lang->goback, '', "class='btn'");?>
  </div>
</div>
<div class='row-table row-table-side-left'>
  <div class='col-side'>
    <div class='panel panel-sm'>
      <div class='panel-heading'>
        <strong><?php echo $lang->testtask->report->select;?></strong>
      </div>
      <div class='panel-body' style='padding-top:0'>
        <form method='post'>
          <?php echo html::checkBox('charts', $lang->testtask->report->charts, $checkedCharts, '', 'block');?>
          <?php echo html::selectAll('', "button", false, 'btn-sm')?>
          <?php echo html::submitButton($lang->testtask->report->create, '', "btn btn-primary btn-sm");?>
        </form>
      </div>
    </div>
  </div>
  <div class='col-main'>
    <div class='panel panel-sm'>
      <div class='panel-heading'>
        <strong>
        <?php echo $lang->testtask->report->common;?>
        <span><?php echo $lang->report->notice->help?></span>
        </strong>
      </div>
      <table class='table active-disabled'>
        <?php foreach($charts as $chartType => $chartOption):?>
        <tr class='text-top'>
          <td>
            <div class='chart-wrapper text-center'>
              <h5><?php echo $lang->testtask->report->charts[$chartType];?></h5>
              <div class='chart-canvas'><canvas id='chart-<?php echo $chartType ?>' width='<?php echo $chartOption->width;?>' height='<?php echo $chartOption->height;?>' data-responsive='true'></canvas></div>
            </div>
          </td>
          <td style='width: 320px'>
            <?php $height = $chartOption->height . 'px'; ?>
            <div style="overflow:auto" class='table-wrapper'>
              <table class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='<?php echo $chartOption->type; ?>' data-target='#chart-<?php echo $chartType ?>' data-animation='false'>
                <thead>
                  <tr>
                    <th class='chart-label' colspan='2'><?php echo $lang->report->item;?></th>
                    <th><?php echo $lang->report->value;?></th>
                    <th><?php echo $lang->report->percent;?></th>
                  </tr>
                </thead>
                <?php foreach($datas[$chartType] as $key => $data):?>
                <tr class='text-center'>
                  <td class='chart-color w-20px'><i class='chart-color-dot icon-circle'></i></td>
                  <td class='chart-label'><?php echo $data->name;?></td>
                  <td class='chart-value'><?php echo $data->value;?></td>
                  <td><?php echo ($data->percent * 100) . '%';?></td>
                </tr>
                <?php endforeach;?>
              </table>
            </div>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
