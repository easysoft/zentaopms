<?php
/**
 * The edit view of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $task->id;?></span>
        <?php echo html::a($this->createLink('testtask', 'view', 'taskID=' . $task->id), $task->name, '_blank');?>
        <small class='text-muted'><?php echo $lang->arrow . $lang->testtask->edit;?></small>
      </h2>
    </div>
    <form method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'> 
        <?php if($config->global->flow != 'onlyTest'):?>
        <tr>
          <th class='w-80px'><?php echo $lang->testtask->project;?></th>
          <td class='w-p35-f'>
          <?php
          echo html::select('project', $projects, $task->project, "class='form-control chosen' onchange='loadProjectRelated(this.value)'");
          echo html::hidden('product', $task->product);
          ?>
          </td>
          <td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th class='w-80px'><?php echo $lang->testtask->build;?></th>
          <td class='w-p35-f'><span id='buildBox'><?php echo html::select('build', $builds, $task->build, "class='form-control chosen'");?></span></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->owner;?></th>
          <td>
            <div id='ownerAndPriBox' class='input-group'>
              <?php echo html::select('owner', $users, $task->owner, "class='form-control chosen'");?>
              <span class='input-group-addon fix-border'><?php echo $lang->testtask->pri;?></span>
              <?php echo html::select('pri', $lang->testtask->priList, $task->pri, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->begin;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', $task->begin, "class='form-control form-date'");?>
              <span class='input-group-addon fix-border'><?php echo $lang->testtask->end;?></span>
              <?php echo html::input('end', $task->end, "class='form-control form-date'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->status;?></th>
          <td><?php echo html::select('status', $lang->testtask->statusList, $task->status,  "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->name;?></th>
          <td colspan='2'><?php echo html::input('name', $task->name, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', htmlspecialchars($task->desc), "rows=10 class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '',  "rows='5' class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->mailto;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::select('mailto[]', $users, str_replace(' ' , '', $task->mailto), "multiple class='form-control chosen'");?>
              <?php echo $this->fetch('my', 'buildContactLists');?>
            </div>
          </td>
        </tr>
        <?php $this->printExtendFields($task, 'table');?>
        <tr>
          <td class='text-center form-actions' colspan='3'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
