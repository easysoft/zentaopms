<?php
/**
 * The view file of view method of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: view.html.php 4955 2013-07-02 01:47:21Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php if(!$todo->private or ($todo->private and $todo->account == $app->user->account)):?>
<style>body{padding:0px;}</style>
<div class='xuanxuan-card'>
  <div class='panel'>
    <div class='panel-heading strong'>
      <span class="label label-id"><?php echo $todo->id?></span>
      <span class='text' title='<?php echo $todo->name;?>'><?php echo $todo->name;?></span>
    </div>
    <div class='panel-body'>
      <table class='table table-data'>
        <tr>
          <th class='w-90px'>
          <?php
          echo $lang->todo->desc;
          if($todo->type == 'bug')   common::printLink('bug',   'view', "id={$todo->idvalue}", '  BUG#'   . $todo->idvalue);
          if($todo->type == 'task')  common::printLink('task',  'view', "id={$todo->idvalue}", '  TASK#'  . $todo->idvalue);
          if($todo->type == 'story') common::printLink('story', 'view', "id={$todo->idvalue}", '  STORY#' . $todo->idvalue);
          ?>
          </th>
          <td><?php echo $todo->desc;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->todo->pri;?></th>
          <td><span title="<?php echo zget($lang->todo->priList, $todo->pri);?>" class='label-pri <?php echo 'label-pri-' . $todo->pri;?>' title='<?php echo zget($lang->todo->priList, $todo->pri, $todo->pri);?>'><?php echo zget($lang->todo->priList, $todo->pri)?></span></td>
        </tr>
        <tr>
          <th><?php echo $lang->todo->status;?></th>
          <td><span class="status-todo status-<?php echo $todo->status;?>"><span class="label label-dot"></span> <?php echo $lang->todo->statusList[$todo->status];?></span></td>
        </tr>
        <tr>
          <th><?php echo $lang->todo->type;?></th>
          <td><?php echo $lang->todo->typeList[$todo->type];?></td>
        </tr>
        <tr>
          <th><?php echo $lang->todo->account;?></th>
          <td><?php echo zget($users, $todo->account);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->todo->date;?></th>
          <td><?php echo $todo->date == '20300101' ? $lang->todo->periods['future'] : formatTime($todo->date, DT_DATE1);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->todo->beginAndEnd;?></th>
          <td><?php if(isset($times[$todo->begin])) echo $times[$todo->begin]; if(isset($times[$todo->end])) echo ' ~ ' . $times[$todo->end];?></td>
        </tr>
        <?php if(isset($todo->assignedTo)):?>
        <tr>
          <th><?php echo $lang->todo->assignTo;?></th>
          <td><?php echo zget($users, $todo->assignedTo);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->todo->assignTo . $lang->todo->date;?></th>
          <td><?php echo formatTime($todo->assignedDate, DT_DATE1);?></td>
        </tr>
        <?php endif;?>
      </table>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
</div>
<?php js::set('todoID', $todo->id);?>
<?php else:?>
<?php echo $lang->todo->thisIsPrivate;?>
<?php endif;?>
<?php include '../../common/view/footer.lite.html.php';?>
