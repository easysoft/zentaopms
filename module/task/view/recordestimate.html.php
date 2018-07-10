<?php
/**
 * The record file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     task
 * @version     $Id: record.html.php 935 2013-01-08 07:49:24Z wwccss@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php $team = array_keys($task->team);?>
<?php js::set('confirmRecord',    (!empty($team) && $task->assignedTo != end($team)) ? $lang->task->confirmTransfer : $lang->task->confirmRecord);?>
<?php js::set('noticeSaveRecord', $lang->task->noticeSaveRecord);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $task->id;?></span>
        <?php echo isonlybody() ? ("<span title='$task->name'>" . $task->name . '</span>') : html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name);?>
        <?php if(!isonlybody()):?>
        <small><?php echo $lang->arrow . $lang->task->logEfforts;?></small>
        <?php endif;?>
      </div>
    </div>
    <form id="recordForm" method='post' target='hiddenwin' style='margin-top:25px'>
      <table class='table table-form table-fixed'>
        <thead>
          <tr class='text-center'>
            <th class="w-id"><?php echo $lang->idAB;?></th>
            <th class="w-120px"><?php echo $lang->task->date;?></th>
            <th class="w-60px"><?php echo $lang->task->consumedThisTime;?></th>
            <th class="w-60px"><?php echo $lang->task->leftThisTime;?></th>
            <th><?php echo $lang->comment;?></th>
            <th class='c-actions-2'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php if(count($estimates)):?>
          <?php foreach($estimates as $estimate):?>
          <tr class="text-center">
            <td><?php echo $estimate->id;?></td>
            <td><?php echo $estimate->date;?></td>
            <td><?php echo $estimate->consumed;?></td>
            <td><?php echo $estimate->left;?></td>
            <td class="text-left"><?php echo $estimate->work;?></td>
            <td align='center' class='c-actions'>
              <?php
              if($task->status == 'wait' or $task->status == 'pause' or $task->status == 'doing')
              {
                  common::printIcon('task', 'editEstimate', "estimateID=$estimate->id", '', 'list', 'pencil', '', 'showinonlybody', true);
                  common::printIcon('task', 'deleteEstimate', "estimateID=$estimate->id", '', 'list', 'trash', 'hiddenwin', 'showinonlybody');
              }
              ?>
            </td>
          </tr>
          <?php endforeach;?>
          <?php endif;?>
      <?php if(!empty($task->team) && $task->assignedTo != $this->app->user->account):?>
        </tbody>
      </table>
    </form>
    <div class="alert with-icon">
      <i class="icon-exclamation-sign"></i>
      <div class="content">
        <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $task->assignedToRealName . '</strong>', $lang->task->logEfforts);?></p>
      </div>
    </div>
    <?php else:?>
          <?php if(in_array($task->status, array('wait', 'pause', 'doing'))):?>
          <?php for($i = 1; $i <= 3; $i++):?>
          <tr class="text-center">
            <td><?php echo $i . html::hidden("id[$i]", $i);?></td>
            <td><?php echo html::input("dates[$i]", '', "class='form-control text-center form-date'");?></td>
            <td><?php echo html::input("consumed[$i]", '', "class='form-control text-center' autocomplete='off'");?></td>
            <td><?php echo html::input("left[$i]", '', "class='form-control text-center left' autocomplete='off'");?></td>
            <td class="text-left"><?php echo html::textarea("work[$i]", '', "class='form-control' style='height:50px;'");?></td>
            <td></td>
          </tr>
          <?php endfor;?>
          <?php endif;?>
          <tr>
            <td colspan='6' class='text-center form-actions'><?php echo html::submitButton('', '', 'btn btn-wide btn-primary') . html::backButton('', '', 'btn btn-wide');?></td>
          </tr>
        </tbody>
      </table>
    </form>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
