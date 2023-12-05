<?php
/**
 * The start file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     testtask
 * @version     $Id: start.html.php 935 2013-01-16 07:49:24Z wwccss@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $testtask->id;?></span>
        <?php echo html::a($this->createLink('testtask', 'view', 'taskID=' . $testtask->id), $testtask->name, '_blank');?>
        <small> <?php echo $lang->arrow . $lang->testtask->block;?></small>
      </h2>
    </div>
    <form class='load-indicator main-form' method='post' id='blockForm'>
      <table class='table table-form'>
        <tr class='hide'>
          <th class='w-45px'><?php echo $lang->testtask->status;?></th>
          <td><?php echo html::hidden('status', 'blocked');?></td>
        </tr>
        <?php $this->printExtendFields($testtask, 'table');?>
        <tr>
          <th class='w-45px'><?php echo $lang->comment;?></th>
          <td><?php echo html::textarea('comment', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center form-actions'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->session->taskList, 'self', '', 'btn btn-wide'); ?></td>
        </tr>
      </table>
    </form>

    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
