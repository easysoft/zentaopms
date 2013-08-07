<?php
/**
 * The setCustom view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <div class='f-left'>
  <?php
  echo "<span id='storyTab'>";    common::printLink('custom', 'setCustom', "module=story",    $lang->custom->story);    echo '</span>';
  echo "<span id='taskTab'>";     common::printLink('custom', 'setCustom', "module=task",     $lang->custom->task);     echo '</span>';
  echo "<span id='bugTab'>";      common::printLink('custom', 'setCustom', "module=bug",      $lang->custom->bug);      echo '</span>';
  echo "<span id='testcaseTab'>"; common::printLink('custom', 'setCustom', "module=testcase", $lang->custom->testcase); echo '</span>';
  echo "<span id='testtaskTab'>"; common::printLink('custom', 'setCustom', "module=testtask", $lang->custom->testtask); echo '</span>';
  echo "<span id='todoTab'>";     common::printLink('custom', 'setCustom', "module=todo",     $lang->custom->todo);     echo '</span>' ;
  echo "<span id='userTab'>";     common::printLink('custom', 'setCustom', "module=user",     $lang->custom->user);     echo '</span>';
  echo "<script>$('#{$module}Tab').addClass('active')</script>";
  ?>
  </div>
</div>
<?php 
  $itemRow = <<<EOT
  <tr class='a-center'>
    <td>
      <input type='text' class="text-1"  value="" name="keys[]">
      <input type='hidden' value="0" name="systems[]">
    </td>
    <td>
      <input type='text' class="w-p98" value="" name="values[]">
    </td>
    <td class='a-left'>
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
  foreach($config->custom->{$module}->fields as $key => $value)
  {
      echo "<span id='{$key}Tab'>" . html::a(inlink('setCustom', "module=$module&field=$key"), $value) . "</span>";
  }
  ?>
  </div>
</div>
<form method='post'>
  <table align='center' class='table-5'>
    <tr>
      <th class='w-100px'><?php echo $lang->custom->key;?></th>
      <th><?php echo $lang->custom->value;?></th>
      <?php if($canAdd):?><th class='w-40px'></th><?php endif;?>
    </tr>
    <?php foreach($fieldList as $key => $value):?>
    <tr class='a-center'>
      <?php $system = isset($dbFields[$key]) ? $dbFields[$key]->system : 1;?>
      <td><?php echo $key; echo html::hidden('keys[]', $key) . html::hidden('systems[]', $system);?></td>
      <td>
        <?php echo html::input("values[]", $value, "class='w-p98'");?>
      </td>
      <?php if($canAdd):?>
      <td class='a-left'>
        <input type='button' onclick='addItem(this)' class='icon-add' value='&nbsp;'></input>
        <?php if(!$system):?><input type='button' onclick='delItem(this)' class='icon-delete' value='&nbsp;'></input><?php endif;?>
      </td>
      <?php endif;?>
    </tr>
    <?php endforeach;?>
    <tfoot><tr><td colspan='<?php $canAdd ? print(3) : print(2);?>' class='a-center'><?php echo html::submitButton() . html::linkButton($lang->custom->restore, inlink('restore', "modult=$modult&field=$field"), 'hiddenwin')?></td></tr><tfoot>
  </table>
</form>
<script>$('#<?php echo $field;?>Tab').addClass('active')</script>
<?php include '../../common/view/footer.html.php';?>
