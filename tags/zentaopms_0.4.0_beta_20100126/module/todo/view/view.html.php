<?php
/**
 * The view file of view method of todo module of ZenTaoMS.
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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     todo
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div class='yui-d0'>
  <?php if(!$todo->private or ($todo->private and $todo->account == $app->user->account)):?>
  <table class='table-1 a-left'> 
    <caption><?php echo $lang->todo->view;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->todo->date;?></th>
      <td><?php echo date('Y-m-d', strtotime($todo->date));?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->type;?></th>
      <td><?php echo $lang->todo->typeList->{$todo->type};?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->pri;?></th>
      <td><?php echo $lang->todo->priList[$todo->pri];?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->name;?></th>
      <td><?php echo $todo->name;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->desc;?></th>
      <td><?php echo nl2br($todo->desc);?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->status;?></th>
      <td><?php echo $lang->todo->statusList[$todo->status];?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->beginAndEnd;?></th>
      <td>
        <?php
        if(isset($times[$todo->begin])) echo $times[$todo->begin];
        if(isset($times[$todo->end]))   echo ' ~ ' . $times[$todo->end];
        ?>
      </td>
    </tr>  
 </table>
 <?php else:?>
 <?php echo $lang->todo->thisIsPrivate;?>
 <?php endif;?>
</div>  
<?php include '../../common/footer.html.php';?>
