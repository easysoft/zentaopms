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
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='yui-d0'>
  <?php if(!$todo->private or ($todo->private and $todo->account == $app->user->account)):?>
  <table class='table-1 a-left'> 
    <caption>
      <div class='f-left'><?php echo $lang->todo->view;?></div>
      <div class='f-right'><?php echo html::a($this->session->todoList, $lang->goback);?></div>
    </caption>
    <tr>
      <th class='rowhead'><?php echo $lang->todo->account;?></th>
      <td><?php echo $todo->account;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->date;?></th>
      <td><?php echo date(DT_DATE1, strtotime($todo->date));?></td>
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
      <td>
        <?php 
        if($todo->type == 'bug')    echo html::a($this->createLink('bug',  'view', "id={$todo->idvalue}"), $todo->name);
        if($todo->type == 'task')   echo html::a($this->createLink('task', 'view', "id={$todo->idvalue}"), $todo->name);
        if($todo->type == 'custom') echo $todo->name;
        ?>
      </td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->todo->desc;?></th>
      <td class='content'><?php echo $todo->desc;?></td>
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
  <div class='a-center f-16px strong'>
    <?php
    if($this->session->todoList)
    {
        $browseLink = $this->session->todoList;
    }
    elseif($todo->account == $app->user->account)
    {
        $browseLink = $this->createLink('my', 'todo');
    }
    else
    {
        $browseLink = $this->createLink('user', 'todo', "account=$todo->account");
    }
    if($todo->account == $app->user->account)
    {
        common::printLink('todo', 'edit',   "todoID=$todo->id", $lang->edit);
        common::printLink('todo', 'delete', "todoID=$todo->id", $lang->delete, 'hiddenwin');
    }
    echo html::a($browseLink, $lang->goback);
    ?>
  </div>
  <?php $actionTheme = 'table'; include '../../common/view/action.html.php';?>
  <?php else:?>
  <?php echo $lang->todo->thisIsPrivate;?>
  <?php endif;?>
</div>  
<?php include '../../common/view/footer.html.php';?>
