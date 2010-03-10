<?php
/**
 * The browse group view file of admin module of ZenTaoMS.
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
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div class="yui-d0 yui-t2">                 
  <div class="yui-b a-center">
  <?php include './menu.html.php';?>
  </div>
  <div class="yui-main">
    <div class="yui-b">
      <table align='center' class='table-1'>
        <caption><?php echo $lang->group->browse;?></caption>
        <tr>
         <th><?php echo $lang->group->id;?></th>
         <th><?php echo $lang->group->name;?></th>
         <th><?php echo $lang->group->desc;?></th>
         <th><?php echo $lang->group->users;?></th>
         <th><?php echo $lang->actions;?></th>
       </tr>
       <?php foreach($groups as $group):?>
       <tr>
         <td><?php echo $group->id;?></td>
         <td><?php echo $group->name;?></td>
         <td><?php echo $group->desc;?></td>
         <td>
         <?php
         foreach($groupUsers[$group->id] as $user)
         {
             echo $user . ' ';
         }
         ?>
         </td>
         <td><nobr>
           <?php echo html::a($this->createLink('group', 'edit',         "groupID=$group->id"), $lang->group->edit);?>
           <?php echo html::a($this->createLink('group', 'managepriv',   "groupID=$group->id"), $lang->group->managePriv);?>
           <?php echo html::a($this->createLink('group', 'managemember', "groupID=$group->id"), $lang->group->manageMember);?>
           <?php echo html::a($this->createLink('group', 'delete',       "groupID=$group->id"), $lang->group->delete, "hiddenwin");?>
           </nobr>
         </td>
       </tr>
       <?php endforeach;?>
      </table>
          <div class='a-right'><?php echo html::a($this->createLink('group', 'create'), $lang->group->create);?></div>
    </div>
  </div>
</div>  
<?php include '../../common/footer.html.php';?>
