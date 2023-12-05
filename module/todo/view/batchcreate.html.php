<?php
/**
 * The batch create view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     todo
 * @version     $Id: create.html.php 2741 2012-04-07 07:24:21Z areyou123456 $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('moduleList', $config->todo->moduleList)?>
<?php js::set('objectsMethod', $config->todo->getUserObjectsMethod)?>
<?php js::set('noOptions', $lang->todo->noOptions);?>
<?php js::set('chosenType', $lang->todo->typeList);?>
<div id="mainContent">
  <div class="main-header">
    <h2><?php echo $lang->todo->batchCreate . $lang->todo->common;?></h2>
    <div class="input-group pull-left">
      <span class="input-group-addon"><?php echo $lang->todo->date;?></span>
      <input type="text" name="date" id='date' value="<?php echo $date;?>" class="form-control form-date" autocomplete="off" />
      <span class="input-group-addon">
        <div class="checkbox-primary">
          <input type='checkbox' name='switchDate' id='switchDate' class='control-time-switch 'onclick='switchDateTodo(this);' />
          <label for='switchDate'><?php echo $lang->todo->periods['future'];?></label>
        </div>
      </span>
    </div>
    <div id='formSettingBtn' class='pull-right btn-toolbar'>
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=todo&section=custom&key=batchCreateFields')?>
      <?php include '../../common/view/customfield.html.php';?>
      <div class="divider"></div>
    </div>
  </div>
  <?php
  $visibleFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if($field)$visibleFields[$field] = '';
  }
  $columns = count($visibleFields) + 3;
  ?>
  <form id='todoBatchAddForm' method='post' target='hiddenwin' action='<?php echo $this->createLink('todo', 'batchCreate');?>' data-ride='table'>
    <table class='table table-form table-fixed with-border'>
      <thead>
        <tr>
          <th class='col-id'><?php echo $lang->idAB;?></th>
          <th class='col-type<?php echo zget($visibleFields, 'type', ' hidden')?>'><?php echo $lang->todo->type;?></th>
          <th class='col-pri<?php echo zget($visibleFields, 'pri', ' hidden')?>'><?php echo $lang->todo->pri;?></th>
          <th class='col-name required'><?php echo $lang->todo->name;?></th>
          <th class='col-desc<?php echo zget($visibleFields, 'desc', ' hidden');?>'><?php echo $lang->todo->desc;?></th>
          <th class='col-assignedTo'><?php echo $lang->todo->assignTo;?></th>
          <th class='col-date<?php echo zget($visibleFields, 'beginAndEnd', ' hidden')?>'><?php echo $lang->todo->beginAndEnd;?></th>
          <th class='col-future'><div class='checkbox-primary check-all visible<?php echo zget($visibleFields, 'beginAndEnd', ' hidden')?>' id="select-all"><label><?php echo $lang->todo->periods['future']?></label></div></th>
        </tr>
      </thead>
      <tbody>
        <?php $pri = 3;?>
        <?php $time = $date != date('Y-m-d') ? key($times) : $time;?>
        <?php for($i = 0; $i < $config->todo->batchCreateNumber; $i++):?>
        <tr class='text-left'>
          <td class='col-id'><?php echo $i+1;?></td>
          <td class="visible <?php echo zget($visibleFields, 'type', 'hidden')?>"><?php echo html::select("types[$i]", $lang->todo->typeList, '', "onchange='loadList(this.value, " . ($i + 1) . ")' class='form-control'");?></td>
          <td class="visible <?php echo zget($visibleFields, 'pri', 'hidden')?>"><?php echo html::select("pris[$i]", $lang->todo->priList, $pri, "class='form-control'");?></td>
          <td class='visible'>
            <div id='<?php echo "nameBox" . ($i+1);?>' class='hidden'><?php echo html::input("names[$i]", '', 'class="text-left form-control"');?></div>
            <div class='<?php echo "nameBox" . ($i+1);?>'><?php echo html::input("names[$i]", '', 'class="text-left form-control"');?></div>
          </td>
          <td <?php echo zget($visibleFields, 'desc', "class='hidden'")?>><?php echo html::textarea("descs[$i]", '', "rows='1' class='form-control'");?></td>
          <?php $assignedTo = $i > 0 ? 'ditto' : $app->user->account;?>
          <?php if($i > 0) $users['ditto'] = $lang->todo->ditto;?>
          <td class='visible'><?php echo html::select("assignedTos[$i]", $users, $assignedTo, "class='form-control chosen'");?></td>
          <td class="visible <?php echo zget($visibleFields, 'beginAndEnd', 'hidden')?>">
            <div class='w-p50 pull-left'>
              <?php echo html::select("begins[$i]", $times, $time, "onchange=\"setBeginsAndEnds($i, 'begin');\" class='form-control chosen control-time-begin'" . (isset($visibleFields['beginAndEnd']) ? '' : " disabled"));?>
            </div>
            <div class='w-p50 pull-left'>
              <?php echo html::select("ends[$i]", $times, '', "onchange=\"setBeginsAndEnds($i, 'end');\" class='form-control chosen control-time-end'" . (isset($visibleFields['beginAndEnd']) ? '' : " disabled"));?>
            </div>
          </td>
          <td class="visible <?php echo zget($visibleFields, 'beginAndEnd', 'hidden')?>">
            <div class='checkbox-primary'>
              <input type='checkbox' name="switchTime[<?php echo $i?>]" id="switchTime<?php echo $i?>" class='control-time-switch' onclick='switchTimeList(<?php echo $i?>);' />
              <label for="switchTime<?php echo $i?>"> <?php echo $lang->todo->periods['future'];?></label>
            </div>
          </td>
        </tr>
        <?php endfor;?>
      </tbody>
      <tfoot>
        <tr>
          <td class='text-center form-actions' colspan='<?php echo isset($visibleFields['beginAndEnd']) ? $columns + 1 : $columns;?>'>
            <?php echo html::hidden('date');?>
            <?php echo html::hidden('switchDate');?>
            <?php echo html::submitButton() . html::backButton();?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php $html = '<div class="divider"></div><button id="closeModal" type="button" class="btn btn-link" data-dismiss="modal"><i class="icon icon-close"></i></button>';?>

<script>
$('#mainContent .main-header .pull-right.btn-toolbar').append(<?php echo json_encode($html)?>);

var $form = $('#todoBatchAddForm').on('change', '.control-time-switch', function()
{
    var $checkbox = $(this);
    $checkbox.closest('.input-group').find('select').attr('disabled', $checkbox.is(':checked') ? 'disabled' : null).trigger('chosen:updated');
});

var $header = $('#todoBatchAddHeader');
var $dateControl = $header.find('.form-date');
$header.find('[name="switchDate"]').on('change', function()
{
    var isDisabled = $(this).is(':checked');
    $dateControl.attr('disabled', isDisabled ? 'disabled' : null);
    $form.find('[name="switchDate"]').prop('checked', isDisabled);
});
$header.find('[name="date"]').on('change', function()
{
    $form.find('[name="date"]').val($(this).val());
});

var batchCreateNum = '<?php echo $config->todo->batchCreate;?>';
</script>
<?php include './footer.html.php';?>
