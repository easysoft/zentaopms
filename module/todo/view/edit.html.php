<?php
/**
 * The create view of todo module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<script language='javascript'>KE.show({id:'desc', items:simpleTools, filterMode:true, imageUploadJson: createLink('file', 'ajaxUpload')});</script>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table class='table-1 a-left'> 
      <caption><?php echo $lang->todo->edit;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->todo->date;?></th>
        <td><?php echo html::select('date', $dates, $todo->date, 'class=select-3');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->type;?></th>
        <td><input type='hidden' name='type' value='<?php echo $todo->type;?>' /><?php echo $lang->todo->typeList->{$todo->type};?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->pri;?></th>
        <td><?php echo html::select('pri', $lang->todo->priList, $todo->pri, 'class=select-3');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->name;?></th>
        <td><div id='nameBox'>
          <?php
          $readType = $todo->type != 'custom' ? 'readonly' : '';
          echo html::input('name', $todo->name, "$readType class=text-1");
          ?>
          </div>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->desc;?></th>
        <td><?php echo html::textarea('desc', htmlspecialchars($todo->desc), "rows=8 class=area-1");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->status;?></th>
        <td><?php echo html::select('status', $lang->todo->statusList, $todo->status, 'class=select-3');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->beginAndEnd;?></th>
        <td>
          <?php echo html::select('begin', $times, $todo->begin, 'onchange=selectNext(); class=select-2') . html::select('end', $times, $todo->end, 'class=select-2');?>
          <input type='checkbox' id='switcher' onclick='switchDateFeature(this);' <?php if($todo->begin == 2400) echo 'checked';?> ><?php echo $lang->todo->lblDisableDate;?>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->private;?></th>
        <td><input type='checkbox' name='private' id='private' value='1' <?php if($todo->private) echo 'checked';?>></td>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'>
          <?php echo html::submitButton() . html::resetButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>  
<?php include './footer.html.php';?>
<script language='Javascript'>switchDateFeature(document.getElementById('switcher'));</script>
