<?php
/**
 * The activate file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: activate.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $bug->id;?></span>
        <?php echo isonlybody() ? ('<span title="' . $bug->title . '">' . $bug->title . '</span>') : html::a($this->createLink('bug', 'view', 'bug=' . $bug->id), $bug->title);?>

        <?php if(!isonlybody()):?>
        <small><?php echo $lang->arrow . $lang->bug->activate;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->bug->assignedTo;?></th>
          <td class='w-p25-f'><?php echo html::select('assignedTo', $users, $bug->resolvedBy, "class='form-control chosen'");?></td><td></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->bug->status;?></th>
          <td><?php echo html::hidden('status', 'active');?></td>
        </tr>
        <?php $this->printExtendFields($bug, 'table');?>
        <tr>
          <th><?php echo $lang->bug->openedBuild;?></th>
          <td colspan='2'><?php echo html::select('openedBuild[]', $builds, $bug->openedBuild, 'size=4 multiple=multiple class="form-control chosen"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->files;?></th>
          <td colspan='2' class='text-left'><?php echo $this->fetch('file', 'buildform');?></td>
        </tr>
        <tr>
          <td class='text-center' colspan='3'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->session->bugList, 'self', '', 'btn btn-wide');?></td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
