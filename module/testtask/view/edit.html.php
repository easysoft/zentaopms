<?php
/**
 * The edit view of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix' title='TESTTASK'><?php echo html::icon($lang->icons['testtask']);?></span>
      <strong><?php echo html::a($this->createLink('testtask', 'view', 'taskID=' . $task->id), $task->name, '_blank');?></strong>
      <small class='text-muted'> <?php echo $lang->testtask->edit;?> <?php echo html::icon($lang->icons['edit']);?></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <tr>
        <th class='w-80px'><?php echo $lang->testtask->project;?></th>
        <td class='w-p25-f'><?php echo html::select('project', $projects, $task->project, "class='form-control chosen'");?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->testtask->build;?></th>
        <td><?php echo html::select('build', $builds, $task->build, "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->testtask->owner;?></th>
        <td><?php echo html::select('owner', $users, $task->owner, "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->testtask->pri;?></th>
        <td><?php echo html::select('pri', $lang->testtask->priList, $task->pri, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->testtask->begin;?></th>
        <td><?php echo html::input('begin', $task->begin, "class='form-control form-date'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->testtask->end;?></th>
        <td><?php echo html::input('end', $task->end, "class='form-control form-date'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->testtask->status;?></th>
        <td><?php echo html::select('status', $lang->testtask->statusList, $task->status,  "class='form-control'");?></td>
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
        <th><?php echo $lang->testtask->report;?></th>
        <td colspan='2'><?php echo html::textarea('report', htmlspecialchars($task->report), "rows=10 class='form-control'");?></td>
      </tr>
      <tr>
        <td></td><td colspan='2'><?php echo html::submitButton() . html::backButton();?> </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
