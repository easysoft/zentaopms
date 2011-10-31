<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: view.html.php 594 2010-03-27 13:44:07Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<script language="Javascript">
var browseType = '<?php echo $browseType;?>';
var moduleID   = '<?php echo $moduleID;?>';
</script>

<div id='featurebar'>
  <div class='f-left'>
    <?php
    echo "<span id='bymoduleTab' onclick=\"browseByModule('$browseType')\"><a href='#'>" . $lang->testtask->byModule . "</a></span> ";
    echo "<span id='allTab'>" . html::a($this->inlink('cases', "taskID=$taskID&browseType=all&param=0"), $lang->testtask->allCases) . "</span>";
    echo "<span id='assignedtomeTab'>" . html::a($this->inlink('cases', "taskID=$taskID&browseType=assignedtome&param=0"), $lang->testtask->assignedToMe) . "</span>";
    ?>
  </div>
  <div class='f-right'>
    <?php
    common::printLink('testtask', 'linkcase', "taskID=$task->id", $lang->testtask->linkCase);
    echo html::a($this->session->testtaskList, $lang->goback);
    ?>
  </div>
</div>

<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side <?php echo $treeClass;?>'>
      <div class='box-title'><?php echo $productName;?></div>
      <div class='box-content'><?php echo $moduleTree;?></div>
    </td>
    <td class='divider <?php echo $treeClass;?>'></td>
    <td>
      <form method='post' action='<?php echo inlink('batchAssign', "task=$task->id");?>' target='hiddenwin'>
      <table class='table-1 tablesorter datatable mb-zero fixed'>
        <thead>
          <tr class='colhead'>
            <th class='w-id'><nobr><?php echo $lang->idAB;?></nobr></th>
            <th class='w-pri'><?php echo $lang->priAB;?></th>
            <th><?php echo $lang->testcase->title;?></th>
            <th><?php echo $lang->testcase->type;?></th>
            <th><?php echo $lang->testtask->assignedTo;?></th>
            <th class='w-user'><?php echo $lang->testtask->lastRun;?></th>
            <th class='w-80px'><?php echo $lang->testtask->lastResult;?></th>
            <th class='w-status'><?php echo $lang->statusAB;?></th>
            <th class='w-160px {sorter: false}'><?php echo $lang->actions;?></th>
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
            <td class='a-left'>
              <?php
              common::printLink('testtask', 'runcase',    "id=$run->id", $lang->testtask->runCase, '', 'class="iframe"');
              common::printLink('testtask', 'results',    "id=$run->id", $lang->testtask->results, '', 'class="iframe"');
              common::printLink('testtask', 'unlinkcase', "id=$run->id", $lang->testtask->unlinkCase, 'hiddenwin');
              if($run->lastResult == 'fail') common::printLink('bug', 'create', "product=$productID&extra=projectID=$task->project,buildID=$task->build,caseID=$run->case,runID=$run->id", $lang->testtask->createBug);
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <table class='table-1'>
        <tr>
          <td><nobr><?php echo "<input type='checkbox' onclick='checkall(this);'> " . $lang->selectAll;?></nobr></td>
          <td colspan='9'>
            <?php
            echo html::select('assignedTo', $users);
            echo html::submitButton($lang->testtask->assign);
            ?>
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
