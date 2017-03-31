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
<?php include '../../common/view/datepicker.html.php';?>
<div class='container mw-800px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['testtask']);?> <strong><?php echo $testtask->id;?></strong></span>
      <strong><?php echo html::a($this->createLink('testtask', 'view', 'taskID=' . $testtask->id), $testtask->name, '_blank');?></strong>
      <small class='text-success'> <?php echo $lang->testtask->block;?> <?php echo html::icon($lang->icons['pause']);?></small>
    </div>
  </div>

  <form class='form-condensed' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='text-left'><?php echo $lang->comment;?></th>
      </tr>
      <tr>
        <td><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
      </tr>
      <tr>
        <td class='text-center'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->session->taskList); ?></td>
      </tr>
    </table>
  </form>
  <div class='main'>
    <?php include '../../common/view/action.html.php';?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
