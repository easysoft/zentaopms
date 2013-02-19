<?php
/**
 * The browse view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/datepicker.html.php';
include '../../common/view/treeview.html.php';
include '../../common/view/colorize.html.php';
js::set('browseType', $browseType);
js::set('moduleID'  , $moduleID);
?>

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
    <?php if($browseType != 'needconfirm') common::printIcon('testcase', 'export', "productID=$productID&orderBy=$orderBy"); ?>
    <?php common::printIcon('testcase', 'batchCreate', "productID=$productID&moduleID=$moduleID");?>
    <?php common::printIcon('testcase', 'create', "productID=$productID&moduleID=$moduleID"); ?>
  </div>
</div>
<div id='querybox' class='<?php if($browseType != 'bysearch') echo 'hidden';?>'></div>
  <table class='cont-lt1'>
    <tr valign='top'>
      <td class='side <?php echo $treeClass;?>'>
        <div class='box-title'><?php echo $productName;?></div>
        <div class='box-content'>
          <?php echo $moduleTree;?>
          <div class='a-right'>
            <?php common::printLink('tree', 'browse', "productID=$productID&view=case", $lang->tree->manage);?>
            <?php common::printLink('tree', 'fix',    "root=$productID&type=case", $lang->tree->fix, 'hiddenwin');?>
          </div>
        </div>
      </td>
      <td class='divider <?php echo $treeClass;?>'></td>
      <td>
        <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
        <form id='batchForm' method='post' action='<?php echo inLink('batchEdit', "from=testcaseBrowse&productID=$productID&orderBy=$orderBy");?>'>
        <table class='table-1 colored tablesorter datatable fixed'>
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
              <th class='w-150px {sorter:false}'><?php echo $lang->actions;?></th>
              <?php endif;?>
            </tr>
            <?php foreach($cases as $case):?>
            <tr class='a-center'>
              <?php $viewLink = inlink('view', "caseID=$case->id");?>
              <td>
                <input type='checkbox' name='caseIDList[]'  value='<?php echo $case->id;?>'/> 
                <?php echo html::a($viewLink, sprintf('%03d', $case->id));?>
              </td>
              <td><span class='<?php echo 'pri' . $case->pri?>'><?php echo $case->pri?></span></td>
              <td class='a-left' title="<?php echo $case->title?>"><?php echo html::a($viewLink, $case->title);?></td>
              <?php if($browseType == 'needconfirm'):?>
              <td class='a-left'><?php echo html::a($this->createLink('story', 'view', "storyID=$case->story"), $case->storyTitle, '_blank');?></td>
              <td><?php $lang->testcase->confirmStoryChange = $lang->confirm; common::printIcon('testcase', 'confirmStoryChange', "caseID=$case->id", '', 'list', '', 'hiddenwin');?></td>
              <?php else:?>
              <td><?php echo $lang->testcase->typeList[$case->type];?></td>
              <td><?php echo $users[$case->openedBy];?></td>
              <td><?php echo $users[$case->lastRunner];?></td>
              <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
              <td><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
              <td><?php echo $lang->testcase->statusList[$case->status];?></td>
              <td class='a-right'>
                <?php
                common::printIcon('testtask', 'runCase', "runID=0&caseID=$case->id&version=$case->version", '', 'list', '', '', 'runCase');
                common::printIcon('testtask', 'results', "runID=0&caseID=$case->id", '', 'list', '', '', 'results');
                common::printIcon('testcase', 'edit',    "caseID=$case->id", $case, 'list');
                common::printIcon('testcase', 'create',  "productID=$case->product&moduleID=$case->module&from=testcase&param=$case->id", $case, 'list', 'copy');
                common::printIcon('testcase', 'delete',  "caseID=$case->id", '', 'list', '', 'hiddenwin');
                common::printIcon('testcase', 'createBug', "product=$case->product&extra=caseID=$case->id,version=$case->version,runID=", $case, 'list', 'createBug');
                ?>
              </td>
              <?php endif;?>
            </tr>
          <?php endforeach;?>
          </thead>
         <tfoot>
           <tr>
             <?php $mergeColums = $browseType == 'needconfirm' ? 5 : 10;?>
             <td colspan='<?php echo $mergeColums?>'>
               <?php if($cases):?>
               <div class='f-left'>
               <?php
               echo html::selectAll() . html::selectReverse(); 
               if(common::hasPriv('testcase', 'batchEdit'))echo html::submitButton($lang->testcase->batchEdit, "onclick='changeAction(\"" . inLink('batchEdit', "from=testcaseBrowse&productID=$productID&orderBy=$orderBy") . "\")'");
               if(common::hasPriv('testtask', 'batchRun')) echo html::submitButton($lang->testtask->batchRun,  "onclick='changeAction(\"" . $this->createLink('testtask', 'batchRun', "productID=$productID&orderBy=$orderBy") . "\")'");
               ?>
               </div>
               <?php endif?>
               <?php $pager->show();?>
             </td>
           </tr>
         </tfoot>
        </table>
      </td>              
    </tr>              
  </table>              
</form>
<?php include '../../common/view/footer.html.php';?>
