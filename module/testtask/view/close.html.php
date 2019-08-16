<?php
/**
 * The close file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     testtask 
 * @version     $Id: close.html.php 935 2013-01-16 07:49:24Z wwccss@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div id='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $testtask->id;?></span>
        <?php echo html::a($this->createLink('testtask', 'view', 'taskID=' . $testtask->id), $testtask->name, '_blank');?>
        <small> <?php echo $lang->arrow . $lang->testtask->close;?></small>
      </h2>
    </div>
    <form class='load-indicator main-form' method='post' id='closeForm'>
      <table class='table table-form'>
        <tr class='hide'>
          <th class='w-60px'><?php echo $lang->testtask->status;?></th>
          <td><?php echo html::hidden('status', 'done');?></td>
        </tr>
        <?php $this->printExtendFields($testtask, 'table');?>
        <tr>
          <th class='w-60px'><?php echo $lang->comment;?></th>
          <td><?php echo html::textarea('comment', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->mailto;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::select('mailto[]', $users, str_replace(' ' , '', $testtask->mailto), "multiple class='form-control chosen'");?>
              <?php if($contactLists) echo html::select('', $contactLists, '', "class='form-control chosen' onchange=\"setMailto('mailto', this.value)\"");?>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan='2' class='text-center form-actions'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->session->taskList, 'self', '', 'btn btn-wide');?></td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
