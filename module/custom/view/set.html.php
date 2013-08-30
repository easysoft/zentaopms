<?php
/**
 * The set view file of custom module of ZenTaoPMS.
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
<?php include '../../common/view/treeview.html.php';?>
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
<?php js::set('itemRow', $itemRow)?>
<?php js::set('module',  $module)?>
<?php js::set('field',   $field)?>
<div id='featurebar'>
  <div class='f-left'>
  <?php
  foreach($lang->custom->object as $object => $name)
  {
      echo "<span id='{$object}Tab'>"; 
      common::printLink('custom', 'set', "module=$object",  $name); 
      echo '</span>';
  }
  ?>
  </div>
</div>
<table class='cont-lt2'>
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->custom->object[$module]?></div>
      <div class='box-content'>
        <ul class='tree'>
        <?php 
          foreach($lang->custom->{$module}->fields as $key => $value)
          {
              echo "<li><span id='{$key}Tab'>" . html::a(inlink('set', "module=$module&field=$key"), $value) . "</span></li>";
          }
        ?>
        </ul>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <form method='post'>
        <table class='table-1'>
          <caption><?echo $lang->custom->object[$module] . ' >> ' . $lang->custom->$module->fields[$field]?></caption>
          <tr>
            <th class='w-100px'><?php echo $lang->custom->key;?></th>
            <th><?php echo $lang->custom->value;?></th>
            <?php if($canAdd):?><th class='w-40px'></th><?php endif;?>
          </tr>
          <?php foreach($fieldList as $key => $value):?>
          <tr class='a-center'>
            <?php $system = isset($dbFields[$key]) ? $dbFields[$key]->system : 1;?>
            <td><?php echo $key === '' ? 'NULL' : $key; echo html::hidden('keys[]', $key) . html::hidden('systems[]', $system);?></td>
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
          <tfoot>
            <tr>
              <td colspan='<?php $canAdd ? print(3) : print(2);?>' class='a-center'>
              <?php 
              $appliedTo = array($currentLang => $lang->custom->currentLang, 'all' => $lang->custom->allLang);
              echo html::radio('lang', $appliedTo, 'all');
              echo html::submitButton();
              if(common::hasPriv('custom', 'restore')) echo html::linkButton($lang->custom->restore, inlink('restore', "module=$module&field=$field"), 'hiddenwin');
              ?>
              </td>
            </tr>
          <tfoot>
        </table>
      </form>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
