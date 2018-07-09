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
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php $browseLink  = $app->session->caseList != false ? $app->session->caseList : $this->createLink('testcase', 'browse', "productID=$case->product");?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-link'");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class='label label-id'><?php echo $case->id;?></span>
      <span class='text' title='<?php echo $case->title;?>' style='color: <?php echo $case->color; ?>'><?php echo $case->title;?></span>

      <?php if($case->deleted):?>
      <span class='label label-danger'><?php echo $lang->product->deleted;?></span>
      <?php endif; ?>

      <?php if($case->version > 1):?>
      <span class='dropdown'>
        &nbsp; <a href='#' data-toggle='dropdown' class='text-muted'><?php echo '#' . $version;?> <span class='caret'></span></a>
        <ul class='dropdown-menu'>
        <?php
        for($i = $case->version; $i >= 1; $i --)
        {
            $class = $i == $version ? " class='active'" : '';
            echo '<li' . $class .'>' . html::a(inlink('view', "caseID=$case->id&version=$i"), '#' . $i) . '</li>'; 
        }
        ?>
        </ul>
      </span>
      <?php endif; ?>
    </div>
  </div>
</div>

<div id="mainContent" class="main-row">
  <div class='main-col col-8'>
    <div class='cell' style='word-break:break-all'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testcase->precondition;?></div>
        <div class="detail-content article-content"><?php echo nl2br($case->precondition);?></div>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testcase->steps;?></div>
        <div class="detail-content">
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
                echo nl2br($step->desc) . "</td>";
                echo "<td class='text-left'>" . nl2br($step->expect) . "</div></td>";
                echo "</tr>";
                $childId ++;
            }
            ?>
          </table>
        </div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $case->files, 'fieldset' => 'true'));?>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
  <div class='side-col col-4'>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->testcase->legendBasicInfo;?></summary>
        <div class="detail-content">
          <table class='table table-data'>
            <?php if($isLibCase):?>
            <tr>
              <th class='w-80px'><?php echo $lang->testcase->lib;?></th>
              <td><?php if(!common::printLink('testsuite', 'library', "libID=$case->lib", $libName)) echo $libName;?></td>
            </tr>
            <?php else:?>
            <tr>
              <th class='w-80px'><?php echo $lang->testcase->product;?></th>
              <td><?php if(!common::printLink('testcase', 'browse', "productID=$case->product", $productName)) echo $productName;?></td>
            </tr>
            <?php if($this->session->currentProductType != 'normal'):?>
            <tr>
              <th><?php echo $lang->product->branch;?></th>
              <td><?php if(!common::printLink('testcase', 'browse', "productID=$case->product&branch=$case->branch", $branchName)) echo $branchName;?></td>
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
                       if(!common::printLink('testcase', 'browse', "productID=$case->product&branch=$module->branch&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
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
                if(isset($case->storyTitle)) echo html::a($this->createLink('story', 'view', "storyID=$case->story", '', true), "#$case->story:$case->storyTitle", '', "class='iframe' data-width='80%'");
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
          </table>
        </div>
      </details>
    </div>
    <?php if(!$isLibCase):?>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->testcase->legendLinkBugs;?></summary>
        <div class="detail-content">
          <table class='table table-data'>
            <?php if($case->fromBug):?>
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
          </table>
        </div>
      </details>
    </div>
    <?php endif;?>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->testcase->legendOpenAndEdit;?></summary>
        <div class="detail-content">
          <table class='table table-data'>
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
        </div>
      </details>
    </div>
  </div>
</div>
<div id="mainActions">
  <?php common::printPreAndNext($preAndNext);?>
  <div class="btn-toolbar">
    <?php common::printBack($browseLink);?>
    <?php if(!$case->deleted):?>
    <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
    <?php
    if(!$isLibCase)
    {
        if(!isonlybody()) echo "<div class='divider'></div>";
        common::printIcon('testtask', 'runCase', "runID=$runID&caseID=$case->id&version=$case->currentVersion", $case, 'button', '', '', 'runCase', false, "data-width='95%'");
        common::printIcon('testtask', 'results', "runID=$runID&caseID=$case->id&version=$case->version", $case, 'button', '', '', 'results', false, "data-width='95%'");

        if($caseFails > 0) common::printIcon('testcase', 'createBug', "product=$case->product&branch=$case->branch&extra=caseID=$case->id,version=$case->version,runID=$runID", $case, 'button', 'bug', '', 'iframe', '', "data-width='90%'");
    }
    if($config->testcase->needReview or !empty($config->testcase->forceReview)) common::printIcon('testcase', 'review', "caseID=$case->id", $case, 'button', '', '', 'iframe');
    ?>
    <?php
    common::printIcon('testcase', 'edit',"caseID=$case->id", $case);
    if(!$isLibCase) common::printIcon('testcase', 'create', "productID=$case->product&branch=$case->branch&moduleID=$case->module&from=testcase&param=$case->id", $case, 'button', 'copy');
    if($isLibCase and common::hasPriv('testsuite', 'createCase')) echo html::a($this->createLink('testsuite', 'createCase', "libID=$case->lib&moduleID=$case->module&param=$case->id", $case), "<i class='icon-copy'></i>", '', "class='btn' title='{$lang->testcase->copy}'");
    common::printIcon('testcase', 'delete', "caseID=$case->id", $case, 'button', '', 'hiddenwin');
    ?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
