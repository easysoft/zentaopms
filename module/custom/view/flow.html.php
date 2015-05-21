<?php
/**
 * The set view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<div id='featurebar'>
  <ul class='nav'>
  <?php
  foreach($lang->custom->object as $object => $name)
  {
      echo "<li id='{$object}Tab'>"; 
      common::printLink('custom', 'set', "module=$object",  $name); 
      echo '</li>';
  }
  echo "<li class='active'>"; 
  common::printLink('custom', 'flow', "",  $lang->custom->flow); 
  echo '</li>';
  ?>
  </ul>
</div>
<div class='main'>
  <form method='post' class='form-condensed' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='w-100px'><?php echo $lang->custom->select;?></th>
        <td class='w-300px'><?php echo html::radio('productproject', $lang->custom->productproject->relation, isset($config->custom->productproject) ? $config->custom->productproject : '0_0');?></td>
        <td><?php echo html::submitButton()?></td>
      </tr>
    </table>
    <div class='alert alert-info'><strong><?php echo $lang->custom->productproject->notice?></strong></div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
