<?php
/**
 * The project view file of dashboard module of ZenTaoMS.
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
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <table class='table-1 tablesorter a-center'>
    <thead>
    <tr class='rowhead'>
      <th><?php echo $lang->project->name;?></th>
      <th><?php echo $lang->project->code;?></th>
      <th><?php echo $lang->project->begin;?></th>
      <th><?php echo $lang->project->end;?></th>
      <th><?php echo $lang->project->status;?></th>
      <th><?php echo $lang->team->role;?></th>
      <th><?php echo $lang->team->joinDate;?></th>
      <th><?php echo $lang->team->workingHour;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($projects as $project):?>
    <tr>
      <td><?php echo html::a($this->createLink('project', 'browse', "projectID=$project->id"), $project->name);?></td>
      <td><?php echo $project->code;?></td>
      <td><?php echo $project->begin;?></td>
      <td><?php echo $project->end;?></td>
      <td><?php echo $project->status;?></td>
      <td><?php echo $project->role;?></td>
      <td><?php echo $project->joinDate;?></td>
      <td><?php echo $project->workingHour;?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table> 
</div>
<?php include '../../common/view/footer.html.php';?>
