<?php
/**
 * The resolve file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: resolve.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php
js::set('page', 'resolve');
js::set('productID', $bug->product);
js::set('bugID', $bug->id);
js::set('released', $lang->build->released);
?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $bug->id;?></span>
        <?php echo isonlybody() ? ('<span title="' . $bug->title . '">' . $bug->title . '</span>') : html::a($this->createLink('bug', 'view', 'bug=' . $bug->id), $bug->title);?>

        <?php if(!isonlybody()):?>
        <small><?php echo $lang->arrow . $lang->bug->resolve;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->bug->resolution;?></th>
          <td class='w-p35-f'><?php echo html::select('resolution', $lang->bug->resolutionList, '', "class='form-control chosen'  onchange=setDuplicate(this.value)");?></td>
          <td></td>
        </tr>
        <tr id='duplicateBugBox' class='hide'>
          <th><?php echo $lang->bug->duplicateBug;?></th>
          <td class='required'><?php echo html::select('duplicateBug', '', '', "class='form-control' placeholder='{$lang->bug->placeholder->duplicate}'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->resolvedBuild;?></th>
          <td id='newBuildExecutionBox' class='hidden required'>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo (!empty($execution) and $execution->type == 'kanban') ? $lang->bug->kanban : $lang->build->execution;?></span>
              <?php echo html::select('buildExecution', $executions, $bug->execution, "class='form-control chosen'");?>
            </div>
          </td>
          <td>
            <div id='resolvedBuildBox'><?php echo html::select('resolvedBuild', $builds, '', "class='form-control picker-select'");?></div>
            <div id='newBuildBox' class='hidden required'><?php echo html::input('buildName', '', "class='form-control' placeholder='{$lang->bug->placeholder->newBuildName}'");?></div>
          </td>
          <td>
            <?php if(common::hasPriv('build', 'create')):?>
            <div class='checkbox-primary'>
              <input type='checkbox' id='createBuild' name='createBuild' value='1' />
              <label for='createBuild'><?php echo $lang->bug->createBuild;?></label>
            </div>
            <?php endif;?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->resolvedDate;?></th>
          <td>
            <div class='datepicker-wrapper datepicker-date'>
              <?php echo html::input('resolvedDate', helper::now(), "class='form-control form-date'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->assignedTo;?></th>
          <td><?php echo html::select('assignedTo', $users, $assignedTo, "class='form-control chosen'");?></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->bug->status;?></th>
          <td><?php echo html::hidden('status', 'resolved');?></td>
        </tr>
        <?php $this->printExtendFields($bug, 'table', "columns=3");?>
        <tr>
          <th><?php echo $lang->bug->files;?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=1&percent=0.85');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='3'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <td class='text-center form-actions' colspan='4'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->session->bugList, 'self', '', 'btn btn-wide');?></td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
