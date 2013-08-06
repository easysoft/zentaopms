<?php
/**
 * The xxx view file of xxx module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     xxx
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<div id='featurebar'>
  <div class='f-left'>
  <?php 
  foreach($this->config->customlang->story->fields as $key => $value)
  {
      echo "<span id='{$key}Tab'>" . html::a(inlink('story', "field=$key"), $value) . "</span>";
  }
  ?>
  </div>
</div>
<form method='post'>
  <table align='center' class='table-5'>
    <tr>
      <th>键</th>
      <th>值</th>
    </tr>
    <?php foreach($fieldList as $key => $value):?>
    <tr class='a-center'>
      <td><?php echo $key;?></td>
      <td><?php echo html::input("{$field}[$key]", $value, "class='text-1'");?></td>
    </tr>
    <?php endforeach;?>
    <tfoot><tr><td colspan='2' class='a-center'><?php echo html::submitButton()?></td></tr><tfoot>
  </table>
</form>
<script>$('#<?php echo $field;?>Tab').addClass('active')</script>
<?php include '../../common/view/footer.html.php';?>
