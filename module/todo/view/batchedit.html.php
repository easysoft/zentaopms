<?php
/**
 * The batch edit view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     todo
 * @version     $Id: create.html.php 2741 2012-04-07 07:24:21Z areyou123456 $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('moduleList', $config->todo->moduleList)?>
<?php js::set('noOptions', $lang->todo->noOptions);?>
<?php js::set('chosenType', $lang->todo->typeList);?>
<?php js::set('objectsMethod', $config->todo->getUserObjectsMethod)?>
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
  $columns = count($visibleFields) + 5;
  ?>
  <form method='post' target='hiddenwin' action='<?php echo $this->inlink('batchEdit', "from=todoBatchEdit");?>'>
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th class='c-id'><?php echo $lang->idAB;?></th>
          <th class='c-date'><?php echo $lang->todo->date;?></th>
          <th class='c-type'><?php echo $lang->todo->type;?></th>
          <th class='c-pri<?php echo zget($visibleFields, 'pri', ' hidden')?>'>   <?php echo $lang->todo->pri;?></th>
          <th class='c-name'><?php echo $lang->todo->name;?></th>
          <th class='c-assignedTo'><?php echo $lang->todo->assignedTo;?></th>
          <th class='c-beginAndEnd<?php echo zget($visibleFields, 'beginAndEnd', ' hidden')?>'><?php echo $lang->todo->beginAndEnd;?></th>
          <th class='c-status<?php echo zget($visibleFields, 'status', ' hidden')?>'>   <?php echo $lang->todo->status;?></th>
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
              echo html::select("types[$todo->id]", $lang->todo->typeList, $todo->type, "onchange='loadList(this.value, {$todo->id}, \"{$todo->type}\", {$todo->objectID})' class='form-control'");
          }
          ?>
        </td>
        <td class="visible <?php echo zget($visibleFields, 'pri', 'hidden')?>"><?php echo html::select("pris[$todo->id]", $lang->todo->priList, $todo->pri, "class='form-control chosen'");?></td>
        <td class='visible'>
          <div id='<?php echo "nameBox" . $todo->id;?>' class='hidden'><?php echo html::input("names[$todo->id]", $todo->name, "class='text-left form-control hiddenwin'"); ?></div>
          <div class='<?php echo "nameBox" . $todo->id;?> text-left'>
          <?php
          if(!in_array($todo->type, $this->config->todo->moduleList))
          {
              echo html::input("names[$todo->id]", $todo->name, "class='form-control'");
          }
          elseif($todo->type == 'task')
          {
              echo html::select("tasks[$todo->id]", $tasks, $todo->objectID, 'class="form-control chosen"');
          }
          elseif($todo->type == 'bug')
          {
              echo html::select("bugs[$todo->id]", $bugs, $todo->objectID, 'class="form-control chosen"');
          }
          elseif($todo->type == 'story')
          {
              echo html::select("stories[$todo->id]", $storys, $todo->objectID, 'class="form-control chosen"');
          }
          elseif($todo->type == 'issue')
          {
              echo html::select("issues[$todo->id]", $issues, $todo->objectID, 'class="form-control chosen"');
          }
          elseif($todo->type == 'risk')
          {
              echo html::select("risks[$todo->id]", $risks, $todo->objectID, 'class="form-control chosen"');
          }
          elseif($todo->type == 'review')
          {
              echo html::select("reviews[$todo->id]", $reviews, $todo->objectID, 'class="form-control chosen"');
          }
          elseif($todo->type == 'testtask')
          {
              echo html::select("testtasks[$todo->id]", $testtasks, $todo->objectID, 'class="form-control chosen"');
          }
          elseif($todo->type == 'opportunity')
          {
              echo html::select("opportunities[$todo->id]", $opportunities, $todo->objectID, 'class="form-control chosen"');
          }
          elseif($todo->type == 'feedback')
          {
              echo html::select("feedbacks[$todo->id]", $feedbacks, $todo->objectID, 'class="form-control chosen"');
          }
          ?>
          </div>
        </td>
        <td class='visible'><?php echo html::select("assignedTos[$todo->id]", $users, $todo->assignedTo, "class='form-control chosen'");?></td>
        <td class="visible <?php echo zget($visibleFields, 'beginAndEnd', 'hidden')?>">
          <div class='input-group'>
            <?php
            echo html::select("begins[$todo->id]", $times, substr($todo->begin, 0, 2) . substr($todo->begin, 3, 2), "onchange=\"setBeginsAndEnds($todo->id, 'begin');\" class='form-control chosen control-time-begin'" . ((isset($visibleFields['beginAndEnd']) && $todo->begin != '') ? '' : " disabled"));
            echo '<span class="input-group-addon fix-border fix-padding"></span>';
            echo html::select("ends[$todo->id]", $times, substr($todo->end, 0, 2) . substr($todo->end, 3, 2), "onchange=\"setBeginsAndEnds($todo->id, 'end');\" class='form-control chosen control-time-end'" . ((isset($visibleFields['beginAndEnd']) && $todo->begin != '') ? '' : " disabled"));
            ?>
            <span class="input-group-addon">
              <div class='checkbox-primary dateSwitcher'>
                <input type='checkbox' name="switchTime[<?php echo $todo->id;?>]" id="switchTime<?php echo $todo->id;?>" data-key="<?php echo $todo->id;?>" onclick='switchTimeList(<?php echo $todo->id?>);' <?php if($todo->begin == '') echo "checked='checked'";?>>
                <label for='switchTime'><?php echo $lang->todo->periods['future'];?></label>
              </div>
            </span>
          </div>
        </td>
        <td class="visible <?php echo zget($visibleFields, 'status', 'hidden')?>"><?php echo html::select("status[$todo->id]", $lang->todo->statusList, $todo->status, "class='form-control chosen'");?></td>
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
