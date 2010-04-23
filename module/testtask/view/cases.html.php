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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     case
 * @version     $Id: view.html.php 594 2010-03-27 13:44:07Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
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
<div class='yui-d0'>
  <form method='post' action='<?php echo inlink('batchAssign', "task=$task->id");?>' target='hiddenwin'>
  <table class='table-1 tablesorter'>
    <caption class='caption-tl'>
      <div class='f-left'><?php echo $lang->testtask->linkedCases;?></div>
      <div class='f-right'>
        <?php
        common::printLink('testtask', 'linkcase', "taskID=$task->id", $lang->testtask->linkCase);
        echo html::a($this->session->testtaskList, $lang->goback);
        ?>
      </div>
    </caption>
    <thead>
      <tr class='colhead'>
        <th class='w-id'><nobr><?php echo $lang->idAB;?></nobr></th>
        <th class='w-pri'><?php echo $lang->priAB;?></th>
        <th class='w-p30'><?php echo $lang->testcase->title;?></th>
        <th><?php echo $lang->testcase->type;?></th>
        <th><?php echo $lang->testtask->assignedTo;?></th>
        <th class='w-80px'><?php echo $lang->testtask->lastRun;?></th>
        <th class='w-80px'><?php echo $lang->testtask->lastResult;?></th>
        <th><?php echo $lang->statusAB;?></th>
        <th class='w-150px {sorter: false}'><?php echo $lang->actions;?></th>
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
        <td><?php $assignedTo = $users[$run->assignedTo]; echo substr($assignedTo, strpos($assignedTo, ':') + 1);?></td>
        <td><?php if(!helper::isZeroDate($run->lastRun)) echo date(DT_MONTHTIME1, strtotime($run->lastRun));?></td>
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
<?php include '../../common/view/footer.html.php';?>
