<?php
/**
 * The create view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
 <?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['todo']);?> <strong><?php echo $todo->id;?></strong></span>
      <strong><?php echo html::a($this->createLink('todo', 'view', 'todo=' . $todo->id), $todo->name);?></strong>
      <small class='text-muted'> <?php echo $lang->todo->edit;?></small>
    </div>
  </div>

  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <tr>
        <th class='w-80px'><?php echo $lang->todo->date;?></th>
        <td class='w-p25-f'>
          <div class='input-group'>
            <?php echo html::input('date', $todo->date, "class='form-control form-date'");?>
            <span class='input-group-addon'><input type='checkbox' id='switchDate' onclick='switchDateTodo(this);'> <?php echo $lang->todo->periods['future'];?></span>
          </div>
        </td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->todo->type;?></th>
        <td><input type='hidden' name='type' value='<?php echo $todo->type;?>' /><?php echo $lang->todo->typeList[$todo->type];?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->pri;?></th>
        <td><?php echo html::select('pri', $lang->todo->priList, $todo->pri, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->name;?></th>
        <td colspan='2'><div id='nameBox'>
          <?php
          $readType = $todo->type != 'custom' ? 'readonly' : '';
          echo html::input('name', $todo->name, "$readType class=form-control");
          ?>
          </div>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', htmlspecialchars($todo->desc), "rows=8 class=area-1");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->todo->status;?></th>
        <td><?php echo html::select('status', $lang->todo->statusList, $todo->status, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->beginAndEnd;?></th>
        <td>
          <div class='input-group'>
            <?php echo html::select('begin', $times, $todo->begin, 'onchange=selectNext(); class="form-control" style="width: 50%"') . html::select('end', $times, $todo->end, 'class="form-control" style="width: 50%"');?>
          </div>
        </td>
        <td>
          <input type='checkbox' id='dateSwitcher' onclick='switchDateFeature(this);' <?php if($todo->begin == 2400) echo 'checked';?> > <?php echo $lang->todo->lblDisableDate;?>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->private;?></th>
        <td><input type='checkbox' name='private' id='private' value='1' <?php if($todo->private) echo 'checked';?>></td>
      </tr>  
      <tr>
        <td></td>
        <td>
          <?php echo html::submitButton() . html::backButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include './footer.html.php';?>
<script language='Javascript'>switchDateFeature(document.getElementById('dateSwitcher'));</script>
