<?php
/**
 * The index view file of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     index
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<form method='post' target='hiddenwin'>
<table class='table-1'>
  <caption><?php echo $lang->index->selectFlow?></caption>
  <tr>
    <td>
    <?php
    foreach($lang->index->flowList as $type => $name)
    {
        $checked = $type == 'full' ? "checked='checked'" : '';
        echo "<p><input type='radio' name='flow' value='$type' $checked> $name</p>";
    }
    ?>
    </td>
  </tr>
  <tr>
    <td><?php echo html::submitButton()?></td>
  </tr>
</table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>

