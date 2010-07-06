<?php
/**
 * The index view file of project module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='yui-d0'>
<table class='table-1'>
  <caption>
    <?php echo $lang->project->selectProject;?>
    <?php echo html::select('projectID', $projects, $project->id, 'onchange="selectProject(this.value);" style="width:200px"');?>
  </caption>
  <tr>
    <th width='60'><?php echo $lang->project->name;?></th>
    <td><?php echo $project->name;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->project->code;?></th>
    <td><?php echo $project->code;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->project->beginAndEnd;?></th>
    <td><?php echo $project->begin . ' ~ ' . $project->end;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->project->goal;?></th>
    <td><?php echo nl2br($project->goal);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->project->desc;?></th>
    <td><?php echo nl2br($project->desc);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->project->status;?></th>
    <td class='<?php echo $project->status;?>'><?php $lang->show($lang->project->statusList, $project->status);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->project->lblStats;?></th>
    <td><?php printf($lang->project->stats, $project->totalEstimate, $project->totalConsumed, $project->totalLeft, 10)?></td>
  </tr>
  <tr>
    <td colspan='2' class='a-right'>
    <?php
    if(common::hasPriv('project', 'edit'))   echo html::a($this->createLink('project', 'edit',   "projectID=$project->id"), $lang->project->edit);
    if(common::hasPriv('project', 'delete')) echo html::a($this->createLink('project', 'delete', "projectID=$project->id"), $lang->project->delete, 'hiddenwin');
    //echo html::a($this->createLink('tree',    'browse', "productID=$productID&view=product"), $lang->tree->manage);
    ?>
    </td>
  </tr>
</table>
<table align='center' class='table-1'>
  <caption>
    <?php echo $lang->project->products;?>
  </caption>
  <tr>
    <td>
      <?php foreach($products as $productID => $productName) echo html::a($this->createLink('product', 'browse', "productID=$productID"), $productName) . '<br />';?>
      <div class='a-right'>
      <?php
      if(common::hasPriv('project', 'manageproducts')) echo html::a($this->createLink('project', 'manageproducts', "projectID=$project->id"), $lang->project->manageProducts);
      if(common::hasPriv('project', 'linkstory'))      echo html::a($this->createLink('project', 'linkstory',      "projectID=$project->id"), $lang->project->linkStory);
      ?>
      </div>
    </td>
  </tr>
</table>
<!--
<table align='center' class='table-1'>
  <caption>
    <?php echo $lang->project->childProjects;?>
  </caption>
  <tr>
    <td>
      <?php foreach($childProjects as $childProjectID => $childProjectName) echo html::a($this->createLink('project', 'browse', "projectID=$childProjectID"), $childProjectName) . '<br />';?>
      <div class='a-right'>
      <?php echo html::a($this->createLink('project', 'managechilds', "projectID=$project->id"), $lang->project->manageChilds) . '<br />';?>
      </div>
    </td>
  </tr>
</table>-->
<table align='center' class='table-1'>
  <caption>
    <?php echo $lang->project->team . $lang->colon . $project->team; ?>
  </caption>
  <thead>
  <tr>
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
</table>
<div class='a-right'>
  <?php if(common::hasPriv('project', 'managemembers')) echo html::a($this->createLink('project', 'managemembers', "projectID=$project->id"), $lang->project->manageMembers) . '<br />';?>
</div>
</div>  
<?php include '../../common/view/footer.html.php';?>
