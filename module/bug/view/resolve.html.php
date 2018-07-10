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
          <th class='w-100px'><?php echo $lang->bug->resolution;?></th>
          <td class='w-p35-f'><?php echo html::select('resolution', $lang->bug->resolutionList, '', "class='form-control chosen'  onchange=setDuplicate(this.value)");?></td>
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
            <span id='resolvedBuildBox'><?php echo html::select('resolvedBuild', $builds, '', "class='form-control chosen'");?></span>
            <span id='newBuildBox' class='hidden'><?php echo html::input('buildName', '', "class='form-control' placeholder='{$lang->bug->placeholder->newBuildName}'");?></span>
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
        <tr>
          <th><?php echo $lang->bug->files;?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=1&percent=0.85');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='3'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <td class='text-center form-actions' colspan='4'><?php echo html::submitButton('', '', 'btn btn-wide btn-primary') . html::linkButton($lang->goback, $this->session->bugList, 'self', '', 'btn btn-wide');?></td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
