<?php
/**
 * The browse view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<script language="Javascript">
var browseType = '<?php echo $browseType;?>';
var moduleID   = '<?php echo $moduleID;?>';
</script>

<div id='featurebar'>
  <div class='f-left'>
    <?php
    echo "<span id='bymoduleTab' onclick=\"browseByModule('$browseType')\"><a href='#'>" . $lang->testcase->moduleCases . "</a></span> ";
    echo "<span id='allTab'>"         . html::a($this->createLink('testcase', 'browse', "productid=$productID&browseType=all&param=0&orderBy=$orderBy&recTotal=0&recPerPage=200"), $lang->testcase->allCases) . "</span>";
    echo "<span id='needconfirmTab'>" . html::a($this->createLink('testcase', 'browse', "productid=$productID&browseType=needconfirm&param=0"), $lang->testcase->needConfirm) . "</span>";
    echo "<span id='bysearchTab' onclick=\"browseBySearch('$browseType')\"><a href='#'><span class='icon-search'></span>{$lang->testcase->bySearch}</a></span> ";
    ?>
  </div>
  <div class='f-right'>
    <?php if($browseType != 'needconfirm') common::printLink('testcase', 'export', "productID=$productID&orderBy=$orderBy", $lang->export, '', 'class="export"'); ?>
    <?php common::printLink('testcase', 'batchCreate', "productID=$productID&moduleID=$moduleID", $lang->testcase->batchCreate); ?>
    <?php common::printLink('testcase', 'create', "productID=$productID&moduleID=$moduleID", $lang->testcase->create); ?>
  </div>
</div>
<div id='querybox' class='<?php if($browseType != 'bysearch') echo 'hidden';?>'><?php echo $searchForm;?></div>
<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side <?php echo $treeClass;?>'>
      <div class='box-title'><?php echo $productName;?></div>
      <div class='box-content'>
        <?php echo $moduleTree;?>
        <div class='a-right'>
          <?php common::printLink('tree', 'browse', "productID=$productID&view=case", $lang->tree->manage);?>
        </div>
      </div>
    </td>
    <td class='divider <?php echo $treeClass;?>'></td>
    <td>
      <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <table class='table-1 colored tablesorter datatable'>
        <thead>
          <tr class='colhead'>
            <th class='w-id'> <?php common::printOrderLink('id',    $orderBy, $vars, $lang->idAB);?></th>
            <th class='w-pri'><?php common::printOrderLink('pri',   $orderBy, $vars, $lang->priAB);?></th>
            <th><?php common::printOrderLink('title', $orderBy, $vars, $lang->testcase->title);?></th>
            <?php if($browseType == 'needconfirm'):?>
            <th><?php common::printOrderLink('story', $orderBy, $vars, $lang->testcase->story);?></th>
            <th class='w-50px'><?php echo $lang->actions;?></th>
            <?php else:?>
            <th class='w-type'>  <?php common::printOrderLink('type',      $orderBy, $vars, $lang->typeAB);?></th>
            <th class='w-user'>  <?php common::printOrderLink('openedBy',  $orderBy, $vars, $lang->openedByAB);?></th>
            <th class='w-80px'>  <?php common::printOrderLink('lastRunner',  $orderBy, $vars, $lang->testtask->lastRunAccount);?></th>
            <th class='w-120px'> <?php common::printOrderLink('lastRunDate',   $orderBy, $vars, $lang->testtask->lastRunTime);?></th>
            <th class='w-80px'>  <?php common::printOrderLink('lastRunResult',$orderBy, $vars, $lang->testtask->lastRunResult);?></th>
            <th class='w-status'><?php common::printOrderLink('status',    $orderBy, $vars, $lang->statusAB);?></th>
            <th class='w-220px {sorter:false}'><?php echo $lang->actions;?></th>
            <?php endif;?>
          </tr>
          <?php foreach($cases as $case):?>
          <tr class='a-center'>
            <?php $viewLink = inlink('view', "caseID=$case->id");?>
            <td><?php echo html::a($viewLink, sprintf('%03d', $case->id));?></td>
            <td><?php echo $case->pri?></td>
            <td class='a-left'><?php echo html::a($viewLink, $case->title);?></td>
            <?php if($browseType == 'needconfirm'):?>
            <td class='a-left'><?php echo html::a($this->createLink('story', 'view', "storyID=$case->story"), $case->storyTitle, '_blank');?></td>
            <td><?php echo html::a(inlink('confirmStoryChange', "caseID=$case->id"), $lang->confirm, 'hiddenwin');?></td>
            <?php else:?>
            <td><?php echo $lang->testcase->typeList[$case->type];?></td>
            <td><?php echo $users[$case->openedBy];?></td>
            <td><?php echo $users[$case->lastRunner];?></td>
            <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
            <td><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
            <td><?php echo $lang->testcase->statusList[$case->status];?></td>
            <td class='a-right'>
              <?php
              common::printLink('testcase', 'create',  "productID=$case->product&moduleID=$case->module&from=testcase&param=$case->id", $lang->copy);
              common::printLink('testcase', 'edit',    "caseID=$case->id", $lang->testcase->buttonEdit);
              common::printLink('testcase', 'delete',  "caseID=$case->id", $lang->delete, 'hiddenwin');
              common::printLink('testtask', 'runCase', "runID=0&caseID=$case->id&version=$case->version", $this->app->loadLang('testtask')->testtask->runCase, '', 'class="runcase"');
              common::printLink('testtask', 'results', "runID=0&caseID=$case->id", $lang->testtask->results, '', 'class="results"');
              if(!($case->lastRunResult == 'fail' and common::printLink('bug', 'create', "product=$case->product&extra=caseID=$case->id,version=$case->version,runID=", $lang->testtask->createBug))) echo $lang->testtask->createBug;
              ?>
            </td>
            <?php endif;?>
          </tr>
        <?php endforeach;?>
        </thead>
        <tfoot><tr><td colspan='10'><?php $pager->show();?></td></tr></tfoot>
      </table>
    </td>              
  </tr>              
</table>              
<?php include '../../common/view/footer.html.php';?>
