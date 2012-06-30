<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: view.html.php 594 2010-03-27 13:44:07Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
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
    common::printLink('testcase', 'export', "productID=$productID&orderBy=$orderBy&taskID=$task->id", '&nbsp;', '', "class='export icon-green-big-export' title='{$lang->export}'");
    common::printLink('testtask', 'linkcase', "taskID=$task->id", $lang->testtask->linkCase);
    echo html::a($this->session->testtaskList, '&nbsp;', '', "class='icon-green-big-goback' title='{$lang->goback}'");
    ?>
  </div>
</div>

<form method='post' name='casesform'>
<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side <?php echo $treeClass;?>'>
      <div class='box-title'><?php echo $productName;?></div>
      <div class='box-content'><?php echo $moduleTree;?></div>
    </td>
    <td class='divider <?php echo $treeClass;?>'></td>
    <td>
    <?php $vars = "taskID=$task->id&browseType=$browseType&param=$param&orderBy=%s&recToal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <table class='table-1 tablesorter datatable mb-zero fixed'>
        <thead>
          <tr class='colhead'>
            <th class='w-id'><nobr><?php common::printOrderLink('id',            $orderBy, $vars, $lang->idAB);?></nobr></th>
            <th class='w-pri'>     <?php common::printOrderLink('pri',           $orderBy, $vars, $lang->priAB);?></th>
            <th>                   <?php common::printOrderLink('title',         $orderBy, $vars, $lang->testcase->title);?></th>
            <th class='w-type'>    <?php common::printOrderLink('type',          $orderBy, $vars, $lang->testcase->type);?></th>
            <th class='w-user'>    <?php common::printOrderLink('assignedTo',    $orderBy, $vars, $lang->testtask->assignedTo);?></th>
            <th class='w-user'>    <?php common::printOrderLink('lastRunner',    $orderBy, $vars, $lang->testtask->lastRunAccount);?></th>
            <th class='w-100px'>   <?php common::printOrderLink('lastRunDate',   $orderBy, $vars, $lang->testtask->lastRunTime);?></th>
            <th class='w-80px'>    <?php common::printOrderLink('lastRunResult', $orderBy, $vars, $lang->testtask->lastRunResult);?></th>
            <th class='w-status'>  <?php common::printOrderLink('status',        $orderBy, $vars, $lang->statusAB);?></th>
            <th class='w-120px {sorter: false}'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($runs as $run):?>
          <tr class='a-center'>
            <td class='a-left'><input type='checkbox' name='caseIDList[]' value='<?php echo $run->case;?>'/> <?php printf('%03d', $run->case);?></td>
            <td><?php echo $run->pri?></td>
            <td class='a-left nobr'><?php echo html::a($this->createLink('testcase', 'view', "caseID=$run->case&version=$run->version"), $run->title, '_blank');?>
            </td>
            <td><?php echo $lang->testcase->typeList[$run->type];?></td>
            <td><?php $assignedTo = $users[$run->assignedTo]; echo substr($assignedTo, strpos($assignedTo, ':') + 1);?></td>
            <td><?php $lastRunner = $users[$run->lastRunner]; echo substr($lastRunner, strpos($lastRunner, ':') + 1);?></td>
            <td><?php if(!helper::isZeroDate($run->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($run->lastRunDate));?></td>
            <td class='<?php echo $run->lastRunResult;?>'><?php if($run->lastRunResult) echo $lang->testcase->resultList[$run->lastRunResult];?></td>
            <td class='<?php echo $run->status;?>'><?php echo $lang->testtask->statusList[$run->status];?></td>
            <td class='a-right'>
              <?php
              common::printLink('testtask', 'runcase',    "id=$run->id", $lang->testtask->runCase, '', 'class="iframe"');
              common::printLink('testtask', 'results',    "id=$run->id", $lang->testtask->results, '', 'class="iframe"');
              common::printLink('testtask', 'unlinkcase', "id=$run->id", $lang->testtask->unlinkCase, 'hiddenwin');
              if(!($run->lastRunResult == 'fail' and common::printLink('bug', 'create', "product=$productID&extra=projectID=$task->project,buildID=$task->build,caseID=$run->case,runID=$run->id", '&nbsp;', '', "class='icon-green-small-case-createBug' title'{$lang->testtask->createBug}'")))
              {
                  echo "<span class='icon-gray-small-case-createBug'>&nbsp;</span>";
              }
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='10'>
              <div class='f-left'>
              <?php echo html::selectAll() . html::selectReverse(); ?>
              <?php if(common::hasPriv('testtask', 'batchAssign')):?>
              <?php echo html::select('assignedTo', $users);?>
              <input class='button-s' value="<?php echo $lang->testtask->batchAssign; ?>" type="button" onclick="casesform.action='<?php echo $this->inLink('batchAssign', "taskID=$task->id");?>';casesform.submit();">
              <?php endif;?>
              </div>
              <?php echo $pager->show();?>
            </td>
          </tr>
        <tfoot>
      </table>
    </td>
  </tr>
</table>
</form>   
<?php include '../../common/view/footer.html.php';?>
