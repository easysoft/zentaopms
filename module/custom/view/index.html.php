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
<?php 
  $itemRow = <<<EOT
  <tr class='a-center'>
    <td><input type='text' class="text-1" type="text" value="" name="keys[]"></td>
    <td>
      <input type='text' class="text-1" type="text" value="" name="values[]">
      <input type='button' onclick='addItem(this)' class='icon-add' value='&nbsp;'></input>
      <input type='button' onclick='delItem(this)' class='icon-delete' value='&nbsp;'></input>
    </td>
  </tr>
EOT;
?>
<script>
function addItem(clickedButton)
{
    $(clickedButton).parent().parent().after(<?php echo json_encode($itemRow);?>);
}
function delItem(clickedButton)
{
    $(clickedButton).parent().parent().remove();
}
</script>
<div id='featurebar'>
  <div class='f-left'>
  <?php 
  foreach($this->config->custom->story->fields as $key => $value)
  {
      echo "<span id='{$key}Tab'>" . html::a(inlink('index', "module=$module&field=$key"), $value) . "</span>";
  }
  ?>
  </div>
</div>
<form method='post'>
  <table align='center' class='table-5'>
    <tr>
      <th class='w-100px'><?php echo $lang->custom->key;?></th>
      <th><?php echo $lang->custom->value;?></th>
    </tr>
    <?php foreach($fieldList as $key => $value):?>
    <tr class='a-center'>
      <td><?php echo $key; echo html::hidden('keys[]', $key);?></td>
      <td>
        <?php echo html::input("values[]", $value, "class='text-1'");?>
        <?php if($canAdd):?><input type='button' onclick='addItem(this)' class='icon-add' value='&nbsp;'></input><?php endif;?>
      </td>
    </tr>
    <?php endforeach;?>
    <tfoot><tr><td colspan='2' class='a-center'><?php echo html::submitButton()?></td></tr><tfoot>
  </table>
</form>
<script>$('#<?php echo $field;?>Tab').addClass('active')</script>
<?php include '../../common/view/footer.html.php';?>
