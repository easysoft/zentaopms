<?php
/**
 * The edit view of testsuite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testsuite
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <strong><?php echo html::a($this->createLink('testsuite', 'view', 'suiteID=' . $suite->id), $suite->name, '_blank');?></strong>
      <small class='text-muted'> <?php echo $lang->testsuite->edit;?> <?php echo html::icon($lang->icons['edit']);?></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <tr>
        <th><?php echo $lang->testsuite->name;?></th>
        <td colspan='2'><?php echo html::input('name', $suite->name, "class='form-control' autocomplete='off'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->testsuite->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', htmlspecialchars($suite->desc), "rows=10 class='form-control'");?></td>
      </tr>
      <?php if($suite->type != 'library'):?>
      <tr>
        <th><?php echo $lang->testsuite->author;?></th>
        <td><?php echo html::radio('type', $lang->testsuite->authorList, $suite->type);?></td>
      </tr>
      <?php endif;?>
      <tr>
        <td></td><td colspan='2'><?php echo html::submitButton() . html::backButton();?> </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
