<?php
/**
 * The ztcomany view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-500px'>
  <div id='titlebar'>
    <div class='heading'><strong><?php echo $lang->admin->ztCompany;?></strong></div>
  </div>
  <form method='post' target='hiddenwin'>
    <table class='table table-form'>
      <?php foreach($fields as $field):?>
      <tr>
        <th><?php echo $field == 'company' ? $lang->company->name : $lang->user->$field;?></th>
        <td><?php echo html::input($field, '', "class='form-control' autocomplete='off'");?></td>
      </tr>
      <?php endforeach;?>
      <tr>
        <th></th>
        <td><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
