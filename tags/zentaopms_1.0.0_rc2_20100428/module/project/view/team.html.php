<?php
/**
 * The team view file of project module of ZenTaoMS.
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
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
 <table align='center' class='table-4 tablesorter'>
   <thead>
   <tr class='colhead'>
     <th><?php echo $lang->team->account;?></th>
     <th><?php echo $lang->team->role;?></th>
     <th><?php echo $lang->team->joinDate;?></th>
     <th><?php echo $lang->team->workingHour;?></th>
     <?php if(common::hasPriv('project', 'unlinkmember')) echo "<th>$lang->actions</th>";?>
   </tr>
   </thead>
   <tbody>
   <?php foreach($teamMembers as $member):?>
   <tr class='a-center'>
     <td>
     <?php 
     if(common::hasPriv('user', 'view')) echo html::a($this->createLink('user', 'view', "account=$member->account"), $member->realname);
     else echo $member->realname;
     ?>
     </td>
     <td><?php echo $member->role;?></td>
     <td><?php echo substr($member->joinDate, 2);?></td>
     <td><?php echo $member->workingHour;?></td>
     <?php if(common::hasPriv('project', 'unlinkmember')) echo "<td>" . html::a($this->createLink('project', 'unlinkmember', "projectID=$project->id&account=$member->account"), $lang->project->unlinkMember, 'hiddenwin') . '</td>';?>
   </tr>
   <?php endforeach;?>
   </tbody>     
   <tfoot>
   <tr><td colspan='5' class='a-right'><?php common::printLink('project', 'managemembers', "projectID=$project->id", $lang->project->manageMembers);?></td></tr>
   </tfoot>
 </table>
 <div class='a-right'>
 </div>
</div>
<?php include '../../common/view/footer.html.php';?>
