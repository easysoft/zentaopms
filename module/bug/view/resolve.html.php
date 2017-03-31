<?php
/**
 * The resolve file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
js::set('page'      , 'resolve');
js::set('productID' , $bug->product);
?>
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
      <td class='w-p35-f'><?php echo html::select('resolution', $lang->bug->resolutionList, '', 'class=form-control onchange=setDuplicate(this.value)');?></td>
      <td></td>
    </tr>
    <tr id='duplicateBugBox' class='hide'>
      <th><?php echo $lang->bug->duplicateBug;?></th>
      <td><?php echo html::input('duplicateBug', '', "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->bug->resolvedBuild;?></th>
      <td id='newBuildProjectBox' class='hidden'>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->build->project;?></span>
          <?php echo html::select('buildProject', $projects, '', "class='form-control chosen'");?>
        </div>
      </td>
      <td>
        <div class='input-group'>
          <span id='resolvedBuildBox'><?php echo html::select('resolvedBuild', $builds, '', "class='form-control chosen'");?></span>
          <?php if(common::hasPriv('build', 'create')):?>
          <span id='newBuildBox' class='hidden'><?php echo html::input('buildName', '', "class='form-control' placeholder='{$lang->bug->placeholder->newBuildName}'");?></span>
          <span class='input-group-addon'><label class="checkbox-inline"><input name="createBuild" value="1" id="createBuild" type="checkbox"> <?php echo $lang->bug->createBuild?></label></span>
          <?php endif;?>
        </div>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->bug->resolvedDate;?></th>
      <td><?php echo html::input('resolvedDate', helper::now(), "class='form-control form-date'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->bug->assignedTo;?></th>
      <td><?php echo html::select('assignedTo', $users, $assignedTo, "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->bug->files;?></th>
      <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=1&percent=0.85');?></td>
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
