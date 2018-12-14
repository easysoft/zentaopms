<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: view.html.php 5000 2013-07-03 08:20:57Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php $browseLink  = $app->session->caseList != false ? $app->session->caseList : $this->createLink('testcase', 'browse', "productID=$case->product");?>
<style>body{padding:0px;}</style>
<div class='xuanxuan-card'>
  <div class='panel'>
    <div class='panel-heading strong'>
      <span class='label label-id'><?php echo $case->id;?></span>
      <span class='text' title='<?php echo $case->title;?>' style='color: <?php echo $case->color; ?>'><?php echo $case->title;?></span>
      <?php if($case->fromCaseID):?>
      <small><?php echo html::icon($lang->icons['testcase']) . " {$lang->testcase->fromCase}$lang->colon$case->fromCaseID";?></small>
      <?php endif;?>
    </div>
    <div class='panel-body'>
      <table class='table table-data'>
        <tr>
          <th><?php echo $lang->testcase->precondition;?></th>
          <td><?php echo nl2br($case->precondition);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testcase->steps;?></th>
          <td>
            <table class='table table-condensed table-hover table-striped table-bordered' id='steps'>
              <thead>
                <tr>
                  <th class='w-50px'><?php echo $lang->testcase->stepID;?></th>
                  <th class='w-p70 text-left'><?php echo $lang->testcase->stepDesc;?></th>
                  <th class='text-left'><?php echo $lang->testcase->stepExpect;?></th>
                </tr>
              </thead>
              <?php
              $stepId = $childId = 0;
              foreach($case->steps as $stepID => $step)
              {
                  $stepClass = "step-{$step->type}";
                  if($step->type == 'group' or $step->type == 'step')
                  {
                      $stepId++;
                      $childId = 0;
                  }
                  if($step->type == 'step') $stepClass = 'step-group';
                  echo "<tr class='step {$stepClass}'>";
                  echo "<th class='step-id'>$stepId</th>";
                  echo "<td class='text-left'><div class='input-group'>";
                  if($step->type == 'item') echo "<span class='step-item-id'>{$stepId}.{$childId}</span>";
                  echo nl2br(str_replace(' ', '&nbsp;', $step->desc)) . "</td>";
                  echo "<td class='text-left'>" . nl2br(str_replace(' ', '&nbsp;', $step->expect)) . "</div></td>";
                  echo "</tr>";
                  $childId ++;
              }
              ?>
            </table>
            <?php echo $this->fetch('file', 'printFiles', array('files' => $case->files, 'fieldset' => 'true'));?>
          </td>
        </tr>
        <tr>
          <th colspan='2' class='text-left stong'><?php echo $lang->testcase->legendBasicInfo;?></th>
        </tr>
        <?php if($isLibCase):?>
        <tr>
          <th class='w-80px'><?php echo $lang->testcase->lib;?></th>
          <td><?php echo $libName;?></td>
        </tr>
        <?php else:?>
        <tr>
          <th class='w-80px'><?php echo $lang->testcase->product;?></th>
          <td><?php echo $productName;?></td>
        </tr>
        <?php if($this->session->currentProductType != 'normal'):?>
        <tr>
          <th><?php echo $lang->product->branch;?></th>
          <td><?php echo $branchName;?></td>
        </tr>
        <?php endif;?>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->testcase->module;?></th>
          <td>
            <?php
            if(empty($modulePath))
            {
                echo "/";
            }
            else
            {
               foreach($modulePath as $key => $module)
               {
                   echo $module->name;
                   if(isset($modulePath[$key + 1])) echo $lang->arrow;
               }
            }
            ?>
          </td>
        </tr>
        <?php if(!$isLibCase and $config->global->flow != 'onlyTest'):?>
        <tr class='nofixed'>
          <th><?php echo $lang->testcase->story;?></th>
          <td>
            <?php
            if(isset($case->storyTitle)) echo "#$case->story:$case->storyTitle");
            if($case->story and $case->storyStatus == 'active' and $case->latestStoryVersion > $case->storyVersion)
            {
                echo "(<span class='warning'>{$lang->story->changed}</span> ";
                echo html::a($this->createLink('testcase', 'confirmStoryChange', "caseID=$case->id"), $lang->confirm, 'hiddenwin');
                echo ")";
            }
            ?>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->testcase->type;?></th>
          <td><?php echo $lang->testcase->typeList[$case->type];?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testcase->stage;?></th>
          <td>
            <?php
            if($case->stage)
            {
                $stags = explode(',', $case->stage);
                foreach($stags as $stage)
                {
                    if(empty($stage)) continue;
                    isset($lang->testcase->stageList[$stage]) ? print($lang->testcase->stageList[$stage]) : print($stage);
                    echo "<br />";
                }
            }
            ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->testcase->pri;?></th>
          <td><span class='label-pri label-pri-<?php echo $case->pri;?>' title='<?php echo zget($lang->testcase->priList, $case->pri);?>'><?php echo zget($lang->testcase->priList, $case->pri)?></span></td>
        </tr>
        <tr>
          <th><?php echo $lang->testcase->status;?></th>
          <td>
            <?php
            echo $lang->testcase->statusList[$case->status];
            if($case->version > $case->currentVersion and $from == 'testtask')
            {
                echo "(<span class='warning'>{$lang->testcase->changed}</span> ";
                echo html::a($this->createLink('testcase', 'confirmchange', "caseID=$case->id"), $lang->confirm, 'hiddenwin', "class='btn btn-mini btn-info'");
                echo ")";
            }
            ?>
          </td>
        </tr>
        <?php if(!$isLibCase):?>
         <tr>
          <th><?php echo $this->app->loadLang('testtask')->testtask->lastRunTime;?></th>
          <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo $case->lastRunDate;?></td>
        </tr>
        <tr>
          <th><?php echo $this->app->loadLang('testtask')->testtask->lastRunResult;?></th>
          <td><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->testcase->keywords;?></th>
          <td><?php echo $case->keywords;?></td>
        </tr>
        <?php if(!$isLibCase):?>
        <tr>
          <th><?php echo $lang->testcase->linkCase;?></th>
          <td>
            <?php
            if(isset($case->linkCaseTitles))
            {
                foreach($case->linkCaseTitles as $linkCaseID => $linkCaseTitle)
                {
                    echo html::a($this->createLink('testcase', 'view', "caseID=$linkCaseID", '', true), "#$linkCaseID $linkCaseTitle", '', "class='iframe' data-width='80%'") . '<br />';
                }
            }
            ?>
          </td>
        </tr>
        <?php endif;?>
        <?php if($case->fromBug):?>
        <tr>
          <th colspan='2' class='text-left stong'><?php echo $lang->testcase->legendLinkBugs;?></th>
        </tr>
        <tr>
          <th class='w-60px'><?php echo $lang->testcase->fromBug;?></th>
          <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$case->fromBug", '', true), $case->fromBugTitle, '', "class='iframe' data-width='80%'");?></td>
        </tr>
        <?php endif;?>
        <?php if($case->toBugs):?>
        <tr>
          <th class='w-60px' valign="top"><?php echo $lang->testcase->toBug;?></th>
          <td>
          <?php
          foreach($case->toBugs as $bugID => $bugTitle)
          {
              echo '<p style="margin-bottom:0;">' . html::a($this->createLink('bug', 'view', "bugID=$bugID", '', true), $bugTitle, '', "class='iframe' data-width='80%'") . '</p>';
          }
          ?>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th colspan='2' class='text-left stong'><?php echo $lang->testcase->legendOpenAndEdit;?></tr>
        </tr>
        <tr>
          <th class='w-60px'><?php echo $lang->testcase->openedBy;?></th>
          <td><?php echo $users[$case->openedBy] . $lang->at . $case->openedDate;?></td>
        </tr>
        <?php if($config->testcase->needReview or !empty($config->testcase->forceReview)):?>
        <tr>
          <th><?php echo $lang->testcase->reviewedBy;?></th>
          <td><?php $reviewedBy = explode(',', $case->reviewedBy); foreach($reviewedBy as $account) echo ' ' . $users[trim($account)]; ?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testcase->reviewedDate;?></th>
          <td><?php if($case->reviewedBy) echo $case->reviewedDate;?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->testcase->lblLastEdited;?></th>
          <td><?php if($case->lastEditedBy) echo $users[$case->lastEditedBy] . $lang->at . $case->lastEditedDate;?></td>
        </tr>
      </table>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
  <div class='page-actions'>
    <?php
    if(!$isLibCase)
    {
        common::printIcon('testtask', 'results', "runID=$runID&caseID=$case->id&version=$case->version", $case, 'button', '', '', 'results', false, "data-width='95%'");
        if($caseFails > 0) common::printIcon('testcase', 'createBug', "product=$case->product&branch=$case->branch&extra=caseID=$case->id,version=$case->version,runID=$runID", $case, 'button', 'bug', '', 'iframe', '', "data-width='90%'");
    }
    if($config->testcase->needReview or !empty($config->testcase->forceReview)) common::printIcon('testcase', 'review', "caseID=$case->id", $case, 'button', '', '', 'iframe');
    ?>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
