<?php
/**
 * The create view of testsuite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testsuite
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->testsuite->create;?></strong>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <tr>
        <th><?php echo $lang->testsuite->name;?></th>
        <td colspan='2'><?php echo html::input('name', '', "class='form-control' autocomplete='off'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testsuite->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', '', "rows=10 class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->testsuite->author;?></th>
        <td><?php echo html::radio('type', $lang->testsuite->authorList, 'private');?></td>
      </tr>  
      <tr>
        <td></td><td colspan='2'><?php echo html::submitButton() . html::backButton();?> </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
