<?php $_GET['onlybody'] = 'no';?>
<table class='table-1 colored tablesorter datatable fixed' id='caseList'>
  <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
  <thead>
    <tr class='colhead'>
      <th class='w-id'>    <?php common::printOrderLink('id',            $orderBy, $vars, $lang->idAB);?></th>
      <th class='w-pri'>   <?php common::printOrderLink('pri',           $orderBy, $vars, $lang->priAB);?></th>
      <th>                 <?php common::printOrderLink('title',         $orderBy, $vars, $lang->testcase->title);?></th>
      <?php if($browseType == 'needconfirm'):?>
      <th>                 <?php common::printOrderLink('story',         $orderBy, $vars, $lang->testcase->story);?></th>
      <th class='w-50px'><?php echo $lang->actions;?></th>
      <?php else:?>
      <th class='w-type'>  <?php common::printOrderLink('type',          $orderBy, $vars, $lang->typeAB);?></th>
      <th class='w-user'>  <?php common::printOrderLink('openedBy',      $orderBy, $vars, $lang->openedByAB);?></th>
      <th class='w-80px'>  <?php common::printOrderLink('lastRunner',    $orderBy, $vars, $lang->testtask->lastRunAccount);?></th>
      <th class='w-120px'> <?php common::printOrderLink('lastRunDate',   $orderBy, $vars, $lang->testtask->lastRunTime);?></th>
      <th class='w-80px'>  <?php common::printOrderLink('lastRunResult', $orderBy, $vars, $lang->testtask->lastRunResult);?></th>
      <th class='w-status'><?php common::printOrderLink('status',        $orderBy, $vars, $lang->statusAB);?></th>
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
      <td class='<?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
      <td class='<?php echo $run->status;?>'><?php echo $lang->testcase->statusList[$case->status];?></td>
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
       if(common::hasPriv('testcase', 'batchEdit'))echo html::submitButton($lang->edit, "onclick='changeAction(\"" . inLink('batchEdit', "from=testcaseBrowse&productID=$productID&orderBy=$orderBy") . "\")'");
       if(common::hasPriv('testtask', 'batchRun')) echo html::submitButton($lang->testtask->runCase,  "onclick='changeAction(\"" . $this->createLink('testtask', 'batchRun', "productID=$productID&orderBy=$orderBy") . "\")'");
       ?>
       </div>
       <?php endif?>
       <?php $pager->show();?>
     </td>
   </tr>
 </tfoot>
</table>
