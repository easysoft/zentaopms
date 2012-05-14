<?php
/**
 * The create view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form method='post' target='hiddenwin'>
  <table class='table-1'> 
    <caption><?php echo $lang->todo->create;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->todo->date;?></th>
      <td><?php echo html::input('date', $date, "class='select-3 date'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->type;?></th>
      <td><?php echo html::select('type', $lang->todo->typeList, '', 'onchange=loadList(this.value); class=select-3');?> 
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->pri;?></th>
      <td><?php echo html::select('pri', $lang->todo->priList, '', 'class=select-3');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->name;?></th>
      <td><div id='nameBox' class='nameBox'><?php echo html::input('name', '', 'class=text-1');?></div></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->desc;?></th>
      <td><?php echo html::textarea('desc', '', "rows='8' class='area-1'");?></textarea>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->status;?></th>
      <td><?php echo html::select('status', $lang->todo->statusList, '', 'class=select-3');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->beginAndEnd;?></th>
      <td>
        <?php echo html::select('begin', $times, $time, 'onchange=selectNext(); class=select-2') . html::select('end', $times, '', 'class=select-2');?>
        <input type='checkbox' id='switchDate' onclick='switchDateFeature(this);'><?php echo $lang->todo->lblDisableDate;?>
      </td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->private;?></th>
      <td><input type='checkbox' name='private' id='private' value='1'></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'>
        <?php echo html::submitButton() . html::resetButton();?>
      </td>
    </tr>
  </table>
</form>
<?php include './footer.html.php';?>
<script language='Javascript'>selectNext();</script>
