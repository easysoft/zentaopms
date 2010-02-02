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
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/tablesorter.html.php';?>
<?php include '../../common/colorbox.html.php';?>
<script language='javascript'>
$(document).ready(function()
{
    $("a.iframe").colorbox({width:900, height:600, iframe:true, transition:'none'});
});

function checkall(checker)
{
    $('input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}
</script>
<div class='yui-d0 yui-t6'>
  <div class='yui-b'>
    <table class='table-1'> 
      <caption><?php echo $lang->testtask->view;?></caption>
      <tr>
        <th class='rowhead w-p25'><?php echo $lang->testtask->project;?></th>
        <td><?php echo $task->projectName;?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testtask->build;?></th>
        <td><?php $task->buildName ? print($task->buildName) : print($task->build);?></td>
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
  </div>
  <div class='yui-main'>
    <div class='yui-b'>
      <form method='post' action='<?php echo inlink('batchAssign', "task=$task->id");?>' target='hiddenwin'>
      <table class='table-1 tablesorter'>
        <caption>
          <div class='f-left'><?php echo $lang->testtask->linkedCases;?></div>
          <div class='f-right'><?php common::printLink('testtask', 'linkcase', "taskID=$task->id", $lang->testtask->linkCase);?></div>
        </caption>
        <thead>
          <tr>
            <th class='w-20px'><nobr><?php echo $lang->testcase->id;?></nobr></th>
            <th><?php echo $lang->testcase->pri;?></th>
            <th class='w-p40'><?php echo $lang->testcase->title;?></th>
            <th><?php echo $lang->testcase->type;?></th>
            <th><?php echo $lang->testtask->assignedTo;?></th>
            <th><?php echo $lang->testtask->lastRun;?></th>
            <th><?php echo $lang->testtask->lastResult;?></th>
            <th><?php echo $lang->testtask->status;?></th>
            <th class='{sorter: false}'><?php echo $lang->action;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($runs as $run):?>
          <tr class='a-center'>
            <td class='a-left'><?php echo "<input type='checkbox' name='cases[]' value='$run->case' /> ";  printf('%03d', $run->case);?></td>
            <td><?php echo $run->pri?></td>
            <td class='a-left nobr'><?php echo html::a($this->createLink('testcase', 'view', "caseID=$run->case&version=$run->version"), $run->title, '_blank');?>
            </td>
            <td><?php echo $lang->testcase->typeList[$run->type];?></td>
            <td><?php echo $users[$run->assignedTo];?></td>
            <td><?php if(substr($run->lastRun, 0, 4) != '0000') echo date('y-m-d H:i', strtotime($run->lastRun));?></td>
            <td class='<?php echo $run->lastResult;?>'><?php if($run->lastResult) echo $lang->testcase->resultList[$run->lastResult];?></td>
            <td class='<?php echo $run->status;?>'><?php echo $lang->testtask->statusList[$run->status];?></td>
            <td>
              <?php
              common::printLink('testtask', 'runcase',    "id=$run->id", $lang->testtask->runCase, '', 'class="iframe"');
              common::printLink('testtask', 'results',    "id=$run->id", $lang->testtask->results, '', 'class="iframe"');
              common::printLink('bug',      'create',     "product=$productID&extra=projectID=$task->project,buildID=$task->build,caseID=$run->case,runID=$run->id", $lang->testtask->createBug);
              common::printLink('testtask', 'unlinkcase', "id=$run->id", $lang->testtask->unlinkCase, 'hiddenwin');
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
          <td><nobr><?php echo "<input type='checkbox' onclick='checkall(this);'> " . $lang->selectAll;?></nobr></td>
          <td colspan='9'>
            <?php
            echo html::select('assignedTo', $users);
            echo html::submitButton($lang->testtask->assign);
            ?>
          </td>
        </tr>
        </tfoot>
      </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/footer.html.php';?>
