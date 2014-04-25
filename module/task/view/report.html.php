<?php
/**
 * The report view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      wenjie<wenjie@cnezsoft.com>
 * @package     project
 * @version     $Id: report.html.php 1594 2011-04-10 11:00:00Z wj $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['task']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['report']);?></small> <?php echo $lang->task->report->common;?></strong>
  </div>
  <div class='actions'>
    <?php echo html::a($this->createLink('project', 'task', "projectID=$projectID&browseType=$browseType"), $lang->goback, '', "class='btn'");?>
  </div>
</div>
<div class='row'>
  <div class='col-md-3 col-lg-2'>
    <div class='panel panel-sm'>
      <div class='panel-heading'>
        <strong><?php echo $lang->task->report->select;?></strong>
      </div>
      <div class='panel-body' style='padding-top:0'>
        <form method='post'>
          <?php echo html::checkBox('charts', $lang->task->report->charts, $checkedCharts, '', 'block')?>
          <div class='btn-group'>
            <?php echo html::selectButton(); ?>
            <?php echo html::submitButton($lang->task->report->create);?>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class='col-md-9 col-lg-10'>
    <div class='panel panel-sm'>
      <div class='panel-heading'>
        <strong><?php echo $lang->task->report->common;?></strong>
      </div>
      <table class='table active-disabled'>
        <?php foreach($charts as $chartType => $chartContent):?>
        <tr valign='top'>
          <td><?php echo $chartContent;?></td>
          <td width='300'>
            <?php $height = zget($lang->task->report->$chartType, 'height', $lang->story->report->options->height) . 'px'; ?>
            <div style="height:<?php echo $height;?>; overflow:auto;">
              <table class='table table-condensed table-hover table-striped table-bordered'>
                <caption class='text-left'><?php echo $lang->task->report->charts[$chartType];?></caption>
                <thead>
                  <tr>
                    <th><?php echo $lang->task->report->$chartType->item;?></th>
                    <th><?php echo $lang->task->report->value;?></th>
                    <th><?php echo $lang->report->percent;?></th>
                  </tr>
                </thead>
                <?php foreach($datas[$chartType] as $key => $data):?>
                <tr class='text-center'>
                  <td><?php echo $data->name;?></td>
                  <td><?php echo $data->value;?></td>
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
<?php echo $renderJS;?>
<?php include '../../common/view/footer.html.php';?>
