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
  <tr class='text-center'>
    <td>
      <input type='text' class="form-control"  value="" name="keys[]">
      <input type='hidden' value="0" name="systems[]">
    </td>
    <td>
      <input type='text' class="form-control" value="" name="values[]">
    </td>
    <td class='text-left'>
      <a href='javascript:void()' class='btn-icon' onclick='addItem(this)'><i class='icon-plus'></i></a>
      <a href='javascript:void()' class='btn-icon' onclick='delItem(this)'><i class='icon-remove'></i></a>
    </td>
  </tr>
EOT;
?>
<?php js::set('itemRow', $itemRow)?>
<?php js::set('module',  $module)?>
<?php js::set('field',   $field)?>
<div id='featurebar'>
  <ul class='nav'>
  <?php
  foreach($lang->custom->object as $object => $name)
  {
      echo "<li id='{$object}Tab'>"; 
      common::printLink('custom', 'set', "module=$object",  $name); 
      echo '</li>';
  }
  ?>
  </ul>
</div>
<div class='side'>
  <div class='list-group'>
  <?php 
    foreach($lang->custom->{$module}->fields as $key => $value)
    {
        echo "<li class='list-group-item' id='{$key}Tab'>" . html::a(inlink('set', "module=$module&field=$key"), $value) . "</li>";
    }
  ?>
  </div>
</div>
<div class='main'>
  <form method='post' class='form-condensed' target='hiddenwin'>
    <div class='panel panel-sm'>
      <div class='panel-heading'>
        <strong><?php echo $lang->custom->object[$module] . ' >> ' . $lang->custom->$module->fields[$field]?></strong>
      </div>
      <?php if($field == 'review'):?>
      <table class='table table-borderless mw-600px'>
        <tr>
          <td><?php echo $lang->custom->storyReview;?></td>
          <td><?php echo html::radio('needReview', $lang->custom->reviewList, $needReview);?></td>
          <td><?php echo html::submitButton();?></td>
        </tr>
      </table>
      <?php else:?>
      <table class='table table-borderless active-disabled table-condensed mw-600px'>
        <tr class='text-center'>
          <th class='w-120px'><?php echo $lang->custom->key;?></th>
          <th><?php echo $lang->custom->value;?></th>
          <?php if($canAdd):?><th class='w-40px'></th><?php endif;?>
        </tr>
        <?php foreach($fieldList as $key => $value):?>
        <tr class='text-center'>
          <?php $system = isset($dbFields[$key]) ? $dbFields[$key]->system : 1;?>
          <td><?php echo $key === '' ? 'NULL' : $key; echo html::hidden('keys[]', $key) . html::hidden('systems[]', $system);?></td>
          <td>
            <?php echo html::input("values[]", $value, "class='form-control'");?>
          </td>
          <?php if($canAdd):?>
          <td class='text-left w-100px'>
            <a href='javascript:void()' class='btn-icon' onclick='addItem(this)'><i class='icon-plus'></i></a>
            <?php if(!$system):?><a href='javascript:void()' onclick='delItem(this)' class='btn-icon'><i class='icon-remove'></i></a><?php endif;?>
          </td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
        <tr>
          <td colspan='<?php $canAdd ? print(3) : print(2);?>' class='text-center'>
          <?php 
          $appliedTo = array($currentLang => $lang->custom->currentLang, 'all' => $lang->custom->allLang);
          echo html::radio('lang', $appliedTo, 'all');
          echo html::submitButton();
          if(common::hasPriv('custom', 'restore')) echo html::linkButton($lang->custom->restore, inlink('restore', "module=$module&field=$field"), 'hiddenwin');
          ?>
          </td>
        </tr>
      </table>
      <?php endif;?>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
