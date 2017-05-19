<?php
/**
 * The create view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('noTodo', $lang->todo->noTodo);?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['todo']);?></span>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->todo->create;?></strong>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <tr>
        <th class='w-80px'><?php echo $lang->todo->date;?></th>
        <td class='w-p25-f'>
          <div class='input-group'>
            <?php echo html::input('date', $date, "class='form-control form-date'");?>
            <span class='input-group-addon'><input type='checkbox' id='switchDate' onclick='switchDateTodo(this);'> <?php echo $lang->todo->periods['future'];?></span>
          </div>
        </td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->todo->type;?></th>
        <td><?php echo html::select('type', $lang->todo->typeList, '', 'onchange=loadList(this.value); class=form-control');?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->pri;?></th>
        <td><?php echo html::select('pri', $lang->todo->priList, '', "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->name;?></th>
        <td colspan='2'>
          <div id='nameBox' class='hidden'><?php echo html::input('name', '', "class='form-control' autocomplete='off'");?></div>
          <div class='nameBox'><?php echo html::input('name', '', "class='form-control' autocomplete='off'");?></div>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', '', "rows='8' class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->status;?></th>
        <td><?php echo html::select('status', $lang->todo->statusList, '', "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->beginAndEnd;?></th>
        <td>
          <div class='input-group'>
            <?php echo html::select('begin', $times, date('Y-m-d') != $date ? key($times) : $time, 'onchange=selectNext(); class="form-control" style="width: 50%;"') . html::select('end', $times, '', 'class="form-control" style="width: 50%; margin-left:-1px"');?>
          </div>
        </td>
        <td><input type='checkbox' id='switchDate' onclick='switchDateFeature(this);'> <?php echo $lang->todo->lblDisableDate;?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->todo->private;?></th>
        <td><input type='checkbox' name='private' id='private' value='1'></td>
      </tr>  
      <tr>
        <td></td>
        <td colspan='2' class='text-center'>
          <?php echo html::submitButton() . html::backButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>

<script language='Javascript'>
var nowTime = '<?php echo $time?>';
var today   = '<?php echo date('Y-m-d')?>';
var start   = '<?php echo key($times)?>';
</script>
<?php include './footer.html.php';?>
