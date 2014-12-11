<?php
/**
 * The resolve file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: resolve.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['bug']);?> <strong><?php echo $bug->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('bug', 'view', 'bug=' . $bug->id), $bug->title, '_blank');?></strong>
    <small class='text-success'> <?php echo $lang->bug->resolve;?> <?php echo html::icon($lang->icons['resolve']);?></small>
  </div>
</div>

<form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->bug->resolution;?></th>
      <td class='w-p35-f'><?php unset($lang->bug->resolutionList['tostory']); echo html::select('resolution', $lang->bug->resolutionList, '', 'class=form-control onchange=setDuplicate(this.value)');?></td><td></td>
    </tr>
    <tr id='duplicateBugBox' class='hide'>
      <th><?php echo $lang->bug->duplicateBug;?></th>
      <td><?php echo html::input('duplicateBug', '', "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->bug->resolvedBuild;?></th>
      <td><?php echo html::select('resolvedBuild', $builds, '', "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->bug->resolvedDate;?></th>
      <td><?php echo html::input('resolvedDate', helper::now(), "class='form-control form-date'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->bug->assignedTo;?></th>
      <td><?php echo html::select('assignedTo', $users, $bug->openedBy, "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->bug->files;?></th>
      <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=2&percent=0.85');?></td>
    </tr>
    <tr>
      <th><?php echo $lang->comment;?></th>
      <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
    </tr>
    <tr>
      <th></th><td colspan='2'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->session->bugList);?></td>
    </tr>
  </table>
</form>
<div class='main'>
  <?php include '../../common/view/action.html.php';?>
</div>
<?php include '../../common/view/footer.html.php';?>
