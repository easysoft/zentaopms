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
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed' id='caseList'>
      <thead>
        <tr>
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
      </thead>
      <?php if($cases):?>
      <tbody>
      <?php foreach($cases as $case):?>
      <tr class='text-center'>
        <?php $viewLink = inlink('view', "caseID=$case->id");?>
        <td>
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
        <td class='<?php if(isset($run)) echo $run->status;?> testcase-<?php echo $case->status?>'><?php echo $case->needconfirm ? "<span class='warning'>{$lang->story->changed}</span>" : $lang->testcase->statusList[$case->status];?></td>
        <td class='text-right'>
          <?php
          common::printIcon('testtask', 'runCase', "runID=0&caseID=$case->id&version=$case->version", '', 'list', 'play', '', 'runCase iframe');
          common::printIcon('testtask', 'results', "runID=0&caseID=$case->id", '', 'list', 'list-alt', '', 'results iframe');
          common::printIcon('testcase', 'edit',    "caseID=$case->id", $case, 'list');
          common::printIcon('testcase', 'create',  "productID=$case->product&branch=$case->branch&moduleID=$case->module&from=testcase&param=$case->id", $case, 'list', 'copy');

          if(common::hasPriv('testcase', 'delete'))
          {
              $deleteURL = $this->createLink('testcase', 'delete', "caseID=$case->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"caseList\",confirmDelete)", '<i class="icon-remove"></i>', '', "title='{$lang->testcase->delete}' class='btn-icon'");
          }

          common::printIcon('testcase', 'createBug', "product=$case->product&branch=$case->branch&extra=caseID=$case->id,version=$case->version,runID=", $case, 'list', 'bug', '', 'iframe');
          ?>
        </td>
        <?php endif;?>
      </tr>
      <?php endforeach;?>
      </tbody>
      <?php endif;?>
