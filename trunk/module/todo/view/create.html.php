<?php
/**
 * The create view of todo module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     todo
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>

<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table class='table-1'> 
      <caption><?php echo $lang->todo->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->todo->date;?></th>
        <td><?php echo html::select('date', $dates, $date, 'class=select-3');?></td>
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
        <td><div id='nameBox'><?php echo html::input('name', '', 'class=text-1');?></div></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->desc;?></th>
        <td><?php echo html::textarea('desc', '', "rows='5' class='area-1'");?></textarea>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->status;?></th>
        <td><?php echo html::select('status', $lang->todo->statusList, '', 'class=select-3');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->beginAndEnd;?></th>
        <td>
          <?php echo html::select('begin', $times, $time, 'onchange=selectNext(); class=select-2') . html::select('end', $times, '', 'class=select-2');?>
          <input type='checkbox' onclick='switchDateFeature(this);'><?php echo $lang->todo->lblDisableDate;?>
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
</div>  
<?php include './footer.html.php';?>
<script language='Javascript'>selectNext();</script>
