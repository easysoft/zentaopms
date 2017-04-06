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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['todo']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $lang->todo->common . $lang->colon . $lang->todo->batchEdit;?></strong>
    <div class='actions'>
      <button type="button" class="btn btn-default" data-toggle="customModal"><i class='icon icon-cog'></i> </button>
    </div>
  </div>
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
$columns = count($visibleFields) + 3;
?>
<form class='form-condensed' method='post' target='hiddenwin' action='<?php echo $this->inlink('batchEdit', "from=todoBatchEdit");?>'>
  <table class='table table-form table-fixed with-border'>
    <thead>
      <tr>
        <th class='w-40px'>   <?php echo $lang->idAB;?></th> 
        <th class='w-100px'>  <?php echo $lang->todo->date;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'type', ' hidden')?>'>  <?php echo $lang->todo->type;?></th>
        <th class='w-80px<?php echo zget($visibleFields, 'pri', ' hidden')?>'>   <?php echo $lang->todo->pri;?></th>
        <th class='red'><?php echo $lang->todo->name;?></th>
        <th <?php echo zget($visibleFields, 'desc', "class='hidden'")?>><?php echo $lang->todo->desc;?></th>
        <th class='w-180px<?php echo zget($visibleFields, 'beginAndEnd', ' hidden')?>'><?php echo $lang->todo->beginAndEnd;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'status', ' hidden')?>'>   <?php echo $lang->todo->status;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($editedTodos as $todo):?>
    <tr class='text-center'>
      <td><?php echo $todo->id . html::hidden("todoIDList[$todo->id]", $todo->id);?></td>
      <td><?php echo html::input("dates[$todo->id]", $todo->date, "class='form-control form-date'");?></td>
      <td <?php echo zget($visibleFields, 'type', "class='hidden'")?>><?php echo html::select("types[$todo->id]", $lang->todo->typeList, $todo->type, "onchange=loadList(this.value,$todo->id) class='form-control'");?></td>
      <td <?php echo zget($visibleFields, 'pri', "class='hidden'")?>><?php echo html::select("pris[$todo->id]", $lang->todo->priList, $todo->pri, 'class=form-control');?></td>
      <td style='overflow:visible'>
        <div id='<?php echo "nameBox" . $todo->id;?>' class='hidden'><? echo html::input("names[$todo->id]", '', "class='text-left form-control hiddenwin' autocomplete='off'"); ?></div>
        <div class='<?php echo "nameBox" . $todo->id;?> text-left'>
        <?php 
        if($todo->type == 'custom')
        {
          echo html::input("names[$todo->id]", $todo->name, "class='form-control' autocomplete='off'"); ;
        }
        elseif($todo->type == 'task')
        {
          echo  html::select("tasks[$todo->id]", $tasks, $todo->idvalue, 'class="form-control chosen"');
        }
        elseif($todo->type == 'bug')
        {
          echo  html::select("bugs[$todo->id]", $bugs, $todo->idvalue, 'class="form-control chosen"');
        }
        ?>
        </div>
      </td>
      <td <?php echo zget($visibleFields, 'desc', "class='hidden'")?>><?php echo html::textarea("descs[$todo->id]", $todo->descs, "rows='1' class='form-control'");?></td>
      <td <?php echo zget($visibleFields, 'beginAndEnd', "class='hidden'")?>>
        <div class='input-group'>
          <?php echo html::select("begins[$todo->id]", $times, $todo->begin, 'class="form-control" style="width: 50%"') . html::select("ends[$todo->id]", $times, $todo->end, 'class="form-control" style="width: 50%"');?>
        </div>
      </td>
      <td <?php echo zget($visibleFields, 'status', "class='hidden'")?>><?php echo html::select("status[$todo->id]", $lang->todo->statusList, $todo->status, "class='form-control'");?></td>
    </tr>  
    <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr><td colspan='<?php echo $columns?>'><?php echo html::submitButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php endif;?>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=todo&section=custom&key=batchEditFields')?>
<?php include '../../common/view/customfield.html.php';?>
<?php include './footer.html.php';?>
