<?php
/**
 * The start file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     testtask
 * @version     $Id: start.html.php 935 2013-01-16 07:49:24Z wwccss@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $testtask->id;?></span>
        <?php echo html::a($this->createLink('testtask', 'view', 'taskID=' . $testtask->id), $testtask->name, '_blank');?>
        <small><?php echo $lang->arrow . $lang->testtask->activate;?></small>
      </div>
    </div>
    <form class='load-indicator main-form' method='post' id='activateForm'>
      <table class='table table-form'>
        <tr>
          <th class='w-45px'><?php echo $lang->comment;?></th>
          <td><?php echo html::textarea('comment', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center form-actions'><?php echo html::submitButton('', '', 'btn btn-wide btn-primary') . ' ' . html::linkButton($lang->goback, $this->session->taskList, 'self', '', 'btn btn-wide');?></td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
