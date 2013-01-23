<?php
/**
 * The setFlow view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<form method='post' target='hiddenwin'>
<table class='table-1'>
  <caption><?php echo $lang->admin->selectFlow?></caption>
  <?php foreach($lang->admin->flowList as $type => $name):?>
  <tr>
    <td>
      <?php
      $checked = $type == 'full' ? "checked='checked'" : '';
      echo "<input type='radio' name='flow' value='$type' $checked> $name";
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  <tr>
    <td><?php echo "<p>{$lang->admin->flowNotice}</p>"?></td>
  </tr>
  <tr>
    <td align='center'><?php echo html::submitButton()?></td>
  </tr>
</table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>

