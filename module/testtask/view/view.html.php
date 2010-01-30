<?php
/**
 * The view file of case module of ZenTaoMS.
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
 * @package     case
 * @version     $Id: view.html.php 355 2010-01-29 06:59:40Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/colorize.html.php';?>
<div class='yui-d0'>
  <table class='table-1'> 
     <caption><?php echo $lang->testtask->view;?></caption>
     <tr>
       <th class='rowhead'><?php echo $lang->testtask->project;?></th>
       <td><?php echo $task->projectName;?></td>
     </tr>  
     <tr>
       <th class='rowhead'><?php echo $lang->testtask->build;?></th>
       <td><?php echo $task->buildName;?></td>
     </tr>  
     <tr>
       <th class='rowhead'><?php echo $lang->testtask->begin;?></th>
       <td><?php echo $task->begin;?>
     </tr>  
     <tr>
       <th class='rowhead'><?php echo $lang->testtask->end;?></th>
       <td><?php echo $task->end;?>
     </tr>  
     <tr>
       <th class='rowhead'><?php echo $lang->testtask->status;?></th>
       <td><?php echo $lang->testtask->statusList[$task->status];?>
     </tr>  
     <tr>
       <th class='rowhead'><?php echo $lang->testtask->name;?></th>
       <td><?php echo $task->name;?>
     </tr>  
     <tr>
       <th class='rowhead'><?php echo $lang->testtask->desc;?></th>
       <td><?php echo nl2br($task->desc);?>
     </tr>  
  </table>
  <table class='table-1 colored tablesorter'>
    <caption><?php echo $lang->testtask->linkedCases;?></caption>
    <thead>
    <tr>
      <th><?php echo $lang->testcase->id;?></th>
      <th><?php echo $lang->testcase->pri;?></th>
      <th><?php echo $lang->testcase->title;?></th>
      <th><?php echo $lang->testcase->type;?></th>
      <th><?php echo $lang->testcase->openedBy;?></th>
      <th><?php echo $lang->testcase->status;?></th>
      <th><?php echo $lang->action;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($cases as $case):?>
    <tr class='a-center'>
      <td><?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$case->id"), sprintf('%03d', $case->id));?></td>
      <td><?php echo $case->pri?></td>
      <td width='50%' class='a-left'><?php echo html::a($this->createLink('testcase', 'view', "caseID=$case->id&version=$case->version"), $case->title, '_blank');?>
      </td>
      <td><?php echo $lang->testcase->typeList[$case->type];?></td>
      <td><?php echo $users[$case->openedBy];?></td>
      <td><?php echo $lang->testcase->statusList[$case->status];?></td>
      <td>
        <?php
        common::printLink('testtask', 'unlinkcase',  "id=$case->id", $lang->testtask->unlinkCase, 'hiddenwin');
        common::printLink('testtask', 'executecase', "taskID=$task->id&caseid=$case->id", $lang->testtask->executeCase);
        ?>
      </td>
    </tr>
    </tbody>
    <?php endforeach;?>
  </table>
</div>
<?php include '../../common/footer.html.php';?>
