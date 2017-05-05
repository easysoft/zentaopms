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
      <?php if($this->config->global->flow != 'onlyTest'):?>
      <tr>
        <th class='w-80px'><?php echo $lang->testtask->project;?></th>
        <td class='w-p35-f'>
          <?php echo html::select('project', $projects, $task->project, "class='form-control chosen' onchange='loadProjectRelated(this.value)'");?>
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
            <?php echo html::select('pri', $lang->testtask->priList, $task->pri, "class='form-control'");?>
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
        <td><?php echo html::select('status', $lang->testtask->statusList, $task->status,  "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->testtask->name;?></th>
        <td colspan='2'><?php echo html::input('name', $task->name, "class='form-control' autocomplete='off'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->testtask->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', htmlspecialchars($task->desc), "rows=10 class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->testtask->mailto;?></th>
        <td colspan='2'>
          <div class='input-group'>
            <?php echo html::select('mailto[]', $users, str_replace(' ' , '', $task->mailto), "multiple class='form-control'");?>
            <?php if($contactLists) echo html::select('', $contactLists, '', "class='form-control chosen' onchange=\"setMailto('mailto', this.value)\"");?>
          </div>
        </td>
      </tr>
      <tr>
        <td></td><td colspan='2'><?php echo html::submitButton() . html::backButton() . html::hidden('product', $task->product);?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
