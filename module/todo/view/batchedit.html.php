<?php
/**
 * The batch edit view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
  </div>
</div>

<form class='form-condensed' method='post' target='hiddenwin' action='<?php echo $this->inlink('batchEdit', "from=todoBatchEdit");?>'>
  <table class='table table-form table-fixed'>
    <thead>
      <tr>
        <th class='w-40px'>   <?php echo $lang->idAB;?></th> 
        <th class='w-100px'>  <?php echo $lang->todo->date;?></th>
        <th class='w-120px'>  <?php echo $lang->todo->type;?></th>
        <th class='w-80px'>   <?php echo $lang->todo->pri;?></th>
        <th class='red'><?php echo $lang->todo->name;?></th>
        <th class='w-180px'>  <?php echo $lang->todo->beginAndEnd;?></th>
        <th class='w-100px'>   <?php echo $lang->todo->status;?></th>
      </tr>
    </thead>
    <?php foreach($editedTodos as $todo):?>
    <tr class='text-center'>
      <td><?php echo $todo->id . html::hidden("todoIDList[$todo->id]", $todo->id);?></td>
      <td><?php echo html::input("dates[$todo->id]", $todo->date, "class='form-control form-date'");?></td>
      <td><?php echo html::select("types[$todo->id]", $lang->todo->typeList, $todo->type, "onchange=loadList(this.value,$todo->id) class='form-control'");?></td>
      <td><?php echo html::select("pris[$todo->id]", $lang->todo->priList, $todo->pri, 'class=form-control');?></td>
      <td style='overflow:visible'>
        <div id='<?php echo "nameBox" . $todo->id;?>' class='hidden'><? echo html::input("names[$todo->id]", '', "class='text-left form-control hiddenwin'"); ?></div>
        <div class='<?php echo "nameBox" . $todo->id;?> text-left'>
        <?php 
        if($todo->type == 'custom')
        {
          echo html::input("names[$todo->id]", $todo->name, "class='form-control'"); ;
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
      <td>
        <div class='input-group'>
          <?php echo html::select("begins[$todo->id]", $times, $todo->begin, 'class="form-control" style="width: 50%"') . html::select("ends[$todo->id]", $times, $todo->end, 'class="form-control" style="width: 50%"');?>
        </div>
      </td>
      <td><?php echo html::select("status[$todo->id]", $lang->todo->statusList, $todo->status, "class='form-control'");?></td>
    </tr>  
    <?php endforeach;?>
    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='7'><div class='text-left text-info'><?php echo $suhosinInfo;?>fdsafsdf</div></td></tr>
    <?php endif;?>
    <tfoot>
      <tr><td colspan='7'><?php echo html::submitButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php include './footer.html.php';?>
