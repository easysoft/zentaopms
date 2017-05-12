<?php
/**
 * The browse data view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
    <?php include '../../common/view/tablesorter.html.php';?>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable' id='caseList'>
      <thead>
        <tr>
          <th class='w-id {sorter:false}'>    <?php common::printOrderLink('id',            $orderBy, $vars, $lang->idAB);?></th>
          <th class='w-pri {sorter:false}'>   <?php common::printOrderLink('pri',           $orderBy, $vars, $lang->priAB);?></th>
          <th class='{sorter:false}'>         <?php common::printOrderLink('title',         $orderBy, $vars, $lang->testcase->title);?></th>
          <?php if($browseType == 'needconfirm'):?>
          <th class='{sorter:false}'>         <?php common::printOrderLink('story',         $orderBy, $vars, $lang->testcase->story);?></th>
          <th class='w-50px {sorter:false}'>  <?php echo $lang->actions;?></th>
          <?php else:?>
          <th class='w-type {sorter:false}'>  <?php common::printOrderLink('type',          $orderBy, $vars, $lang->typeAB);?></th>
          <th class='w-user {sorter:false}'>  <?php common::printOrderLink('openedBy',      $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='w-80px {sorter:false}'>  <?php common::printOrderLink('lastRunner',    $orderBy, $vars, $lang->testtask->lastRunAccount);?></th>
          <th class='w-120px {sorter:false}'> <?php common::printOrderLink('lastRunDate',   $orderBy, $vars, $lang->testtask->lastRunTime);?></th>
          <th class='w-80px {sorter:false}'>  <?php common::printOrderLink('lastRunResult', $orderBy, $vars, $lang->testtask->lastRunResult);?></th>
          <th class='w-100px {sorter:false}'> <?php common::printOrderLink('status',        $orderBy, $vars, $lang->statusAB);?></th>
          <th class='w-30px' title='<?php echo $lang->testcase->bugs?>'> <?php echo $lang->testcase->bugsAB;?></th>
          <th class='w-30px' title='<?php echo $lang->testcase->results?>'> <?php echo $lang->testcase->resultsAB;?></th>
          <th class='w-30px' title='<?php echo $lang->testcase->stepNumber?>'> <?php echo $lang->testcase->stepNumberAB;?></th>
          <th class='<?php echo ($config->testcase->needReview or !empty($config->testcase->forceReview)) ? 'w-170px' : 'w-150px'?> {sorter:false}'><?php echo $lang->actions;?></th>
          <?php endif;?>
        </tr>
      </thead>
      <?php if($cases):?>
      <tbody>
      <?php foreach($cases as $case):?>
      <tr class='text-center'>
        <?php $viewLink = inlink('view', "caseID=$case->id&version=$case->version");?>
        <td class='cell-id'>
          <input type='checkbox' name='caseIDList[]'  value='<?php echo $case->id;?>'/> 
          <?php echo html::a($viewLink, sprintf('%03d', $case->id));?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $case->pri, $case->pri)?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?></span></td>
        <td class='text-left' title="<?php echo $case->title?>">
          <?php if($case->branch) echo "<span title='{$lang->product->branchName[$product->type]}' class='label label-branch label-badge'>{$branches[$case->branch]}</span> "?>
          <?php if($modulePairs and $case->module) echo "<span title='{$lang->testcase->module}' class='label label-info label-badge'>{$modulePairs[$case->module]}</span> "?>
          <?php echo html::a($viewLink, $case->title, null, "style='color: $case->color'");?>
        </td>
        <?php if($browseType == 'needconfirm'):?>
        <td class='text-left'><?php echo html::a($this->createLink('story', 'view', "storyID=$case->story"), $case->storyTitle, '_blank');?></td>
        <td><?php $lang->testcase->confirmStoryChange = $lang->confirm; common::printIcon('testcase', 'confirmStoryChange', "caseID=$case->id", '', 'list', '', 'hiddenwin');?></td>
        <?php else:?>
        <td><?php echo $lang->testcase->typeList[$case->type];?></td>
        <td><?php echo $users[$case->openedBy];?></td>
        <td><?php echo $users[$case->lastRunner];?></td>
        <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
        <td class='<?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
        <td class='<?php if(isset($run)) echo $run->status;?> testcase-<?php echo $case->status?>'>
          <?php
          if($case->needconfirm)
          {
              echo "(<span class='warning'>{$lang->story->changed}</span> ";
              echo html::a($this->createLink('testcase', 'confirmStoryChange', "caseID=$case->id"), $lang->confirm, 'hiddenwin');
              echo ")";
          }
          else
          {
              echo $lang->testcase->statusList[$case->status];
          }
          ?>
        </td>
        <td><?php echo (common::hasPriv('testcase', 'bugs') and $case->bugs) ? html::a(inlink('bugs', "runID=0&caseID={$case->id}"), $case->bugs, '', "class='iframe'") : $case->bugs;?></td>
        <td><?php echo (common::hasPriv('testtask', 'results') and $case->results) ? html::a($this->createLink('testtask', 'results', "runID=0&caseID={$case->id}"), $case->results, '', "class='iframe'") : $case->results;?></td>
        <td><?php echo $case->stepNumber;?></td>
        <td class='text-right'>
          <?php
          common::printIcon('testtask', 'runCase', "runID=0&caseID=$case->id&version=$case->version", '', 'list', 'play', '', 'runCase iframe', false, "data-width='95%'");
          common::printIcon('testtask', 'results', "runID=0&caseID=$case->id", '', 'list', 'list-alt', '', 'results iframe', false, "data-width='95%'");
          if($config->testcase->needReview or !empty($config->testcase->forceReview)) common::printIcon('testcase', 'review',  "caseID=$case->id", $case, 'list', 'review', '', 'iframe');
          common::printIcon('testcase', 'edit',    "caseID=$case->id", $case, 'list');
          common::printIcon('testcase', 'create',  "productID=$case->product&branch=$case->branch&moduleID=$case->module&from=testcase&param=$case->id", $case, 'list', 'copy');

          if(common::hasPriv('testcase', 'delete'))
          {
              $deleteURL = $this->createLink('testcase', 'delete', "caseID=$case->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"caseList\",confirmDelete)", '<i class="icon-remove"></i>', '', "title='{$lang->testcase->delete}' class='btn-icon'");
          }

          common::printIcon('testcase', 'createBug', "product=$case->product&branch=$case->branch&extra=caseID=$case->id,version=$case->version,runID=", $case, 'list', 'bug', '', 'iframe',false,"data-width='95%'");
          ?>
        </td>
        <?php endif;?>
      </tr>
      <?php endforeach;?>
      </tbody>
      <?php endif;?>
