<?php
/**
 * The batch edit view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     todo
 * @version     $Id: create.html.php 2741 2012-04-07 07:24:21Z areyou123456 $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->todo->common . $lang->colon . $lang->todo->batchEdit;?></h2>
    <div class='input-group pull-right'>
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=todo&section=custom&key=batchEditFields')?>
      <?php include '../../common/view/customfield.html.php';?>    </div>
  </div>
  <?php if(isset($suhosinInfo)):?>
  <div class='alert alert-info'><?php echo $suhosinInfo;?></div>
  <?php else:?>
  <?php
  $visibleFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if($field)$visibleFields[$field] = '';
  }
  $columns = count($visibleFields) + 4;
  ?>
  <form method='post' target='hiddenwin' action='<?php echo $this->inlink('batchEdit', "from=todoBatchEdit");?>'>
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th class='w-40px'>   <?php echo $lang->idAB;?></th>
          <th class='w-100px'>  <?php echo $lang->todo->date;?></th>
          <th class='w-110px'>  <?php echo $lang->todo->type;?></th>
          <th class='w-100px<?php echo zget($visibleFields, 'pri', ' hidden')?>'>   <?php echo $lang->todo->pri;?></th>
          <th><?php echo $lang->todo->name;?></th>
          <th <?php echo zget($visibleFields, 'desc', "class='hidden'")?>><?php echo $lang->todo->desc;?></th>
          <th class='w-300px<?php echo zget($visibleFields, 'beginAndEnd', ' hidden')?>'><?php echo $lang->todo->beginAndEnd;?></th>
          <th class='w-120px<?php echo zget($visibleFields, 'status', ' hidden')?>'>   <?php echo $lang->todo->status;?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($editedTodos as $todo):?>
      <tr class='text-left'>
        <td><?php echo $todo->id . html::hidden("todoIDList[$todo->id]", $todo->id);?></td>
        <td><?php echo html::input("dates[$todo->id]", $todo->date, "class='form-control form-date'");?></td>
        <td class='text-center'>
          <?php
          if($todo->type == 'cycle')
          {
              echo html::hidden("types[$todo->id]", $todo->type); 
              echo $lang->todo->cycle;
          }
          else
          {
              echo html::select("types[$todo->id]", $lang->todo->typeList, $todo->type, "onchange='loadList(this.value, " . $todo->id . ")' class='form-control'");
          }
          ?>
        </td>
        <td style='overflow:visible' <?php echo zget($visibleFields, 'pri', "class='hidden'")?>><?php echo html::select("pris[$todo->id]", $lang->todo->priList, $todo->pri, "class='form-control chosen'");?></td>
        <td style='overflow:visible'>
          <div id='<?php echo "nameBox" . $todo->id;?>' class='hidden'><?php echo html::input("names[$todo->id]", $todo->name, "class='text-left form-control hiddenwin'"); ?></div>
          <div class='<?php echo "nameBox" . $todo->id;?> text-left'>
          <?php
          if($todo->type == 'custom' or $todo->type == 'cycle')
          {
              echo html::input("names[$todo->id]", $todo->name, "class='form-control'"); ;
          }
          elseif($todo->type == 'task')
          {
              echo html::select("tasks[$todo->id]", $tasks, $todo->idvalue, 'class="form-control chosen"');
          }
          elseif($todo->type == 'bug')
          {
              echo html::select("bugs[$todo->id]", $bugs, $todo->idvalue, 'class="form-control chosen"');
          }
          elseif($todo->type == 'story')
          {
              echo html::select("storys[$todo->id]", $storys, $todo->idvalue, 'class="form-control chosen"');
          }
          ?>
          </div>
        </td>
        <td <?php echo zget($visibleFields, 'desc', "class='hidden'")?>><?php echo html::textarea("descs[$todo->id]", $todo->desc, "rows='1' class='form-control'");?></td>
        <td <?php echo zget($visibleFields, 'beginAndEnd', "class='hidden'")?> style='overflow:visible'>
          <div class='input-group'>
            <?php
            echo html::select("begins[$todo->id]", $times, $todo->begin, "onchange=\"setBeginsAndEnds($todo->id, 'begin');\" class='form-control chosen control-time-begin'" . ((isset($visibleFields['beginAndEnd']) && $todo->begin != '2400') ? '' : " disabled"));
            echo '<span class="input-group-addon fix-border fix-padding"></span>';
            echo html::select("ends[$todo->id]", $times, $todo->end, "onchange=\"setBeginsAndEnds($todo->id, 'end');\" class='form-control chosen control-time-end'" . ((isset($visibleFields['beginAndEnd']) && $todo->begin != '2400') ? '' : " disabled"));
            ?>
            <span class="input-group-addon">
              <div class='checkbox-primary dateSwitcher'>
                <input type='checkbox' name="switchTime[<?php echo $todo->id;?>]" id="switchTime<?php echo $todo->id;?>" data-key="<?php echo $todo->id;?>" onclick='switchTimeList(<?php echo $todo->id?>);' <?php if($todo->begin == '2400') echo "checked='checked'";?>>
                <label for='switchTime'><?php echo $lang->todo->periods['future'];?></label>
              </div>
            </span>
          </div>
        </td>
        <td <?php echo zget($visibleFields, 'status', "class='hidden'")?> style='overflow:visible'><?php echo html::select("status[$todo->id]", $lang->todo->statusList, $todo->status, "class='form-control chosen'");?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='<?php echo $columns?>' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
  <?php endif;?>
</div>
<?php include './footer.html.php';?>
