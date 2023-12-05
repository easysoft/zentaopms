<?php
/**
 * The edit view of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('projectID', $task->project);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $task->id;?></span>
        <?php echo html::a($this->createLink('testtask', 'view', 'taskID=' . $task->id), $task->name, '_blank');?>
        <small class='text-muted'><?php echo $lang->arrow . $lang->testtask->edit;?></small>
      </h2>
    </div>
    <form method='post' class="main-form form-ajax" enctype="multipart/form-data" id='dataform'>
      <table class='table table-form'>
        <?php echo html::hidden('product', $task->product);?>
        <?php if(!empty($project) and !$project->multiple):?>
        <?php echo html::hidden('execution', $task->execution);?>
        <?php else:?>
        <tr class='<?php echo ($app->tab == 'execution' and $task->execution) ? 'hide' : '';?>'>
          <th class='w-100px'><?php echo $lang->testtask->execution;?></th>
          <td class='w-p35-f'>
            <?php echo html::select('execution', $executions, $task->execution, "class='form-control chosen' onchange='loadExecutionRelated(this.value)'");?>
          </td>
          <td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th class='w-100px'><?php echo $lang->testtask->build;?></th>
          <td class='w-p35-f'><span id='buildBox'><?php echo html::select('build', $builds, $task->build, "class='form-control chosen'");?></span></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->type;?></th>
          <td><?php echo html::select('type[]', $lang->testtask->typeList, $task->type, "class='form-control picker-select' multiple");?></td>
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
          <th><?php echo $lang->testtask->testreport;?></th>
          <td><?php echo html::select('testreport', array('') + $testreports, $task->testreport,  "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->name;?></th>
          <td colspan='2'><?php echo html::input('name', $task->name, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->desc;?></th>
          <td colspan='2'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=testtask&link=desc');?>
            <?php echo html::textarea('desc', htmlSpecialString($task->desc), "rows=10 class='form-control'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '',  "rows='5' class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->files;?></th>
          <td colspan='3'>
            <?php echo $this->fetch('file', 'printFiles', array('files' => $task->files, 'fieldset' => 'false', 'object' => $task, 'method' => 'edit'));?>
            <?php echo $this->fetch('file', 'buildform');?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->mailto;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::select('mailto[]', $users, str_replace(' ' , '', $task->mailto), "multiple class='form-control picker-select'");?>
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
