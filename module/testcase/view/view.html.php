<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: view.html.php 5000 2013-07-03 08:20:57Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php $browseLink  = $app->session->caseList ? $app->session->caseList : $this->createLink('testcase', 'browse', "productID=$case->product");?>
<?php js::set('sysurl', common::getSysUrl());?>
<?php js::set('tab', $app->tab);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class='label label-id'><?php echo $case->id;?></span>
      <span class='text' title='<?php echo $case->title;?>' style='color: <?php echo $case->color; ?>'><?php echo $case->title;?></span>
      <?php if($case->fromCaseID):?>
      <small><?php echo html::a(helper::createLink('testcase', 'view', "caseID=$case->fromCaseID"), html::icon($lang->icons['testcase']) . " {$lang->testcase->fromCase}$lang->colon$case->fromCaseID");?></small>
      <?php endif;?>

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
  <?php if(!isonlybody()):?>
  <div class='btn-toolbar pull-right'>
    <button type='button' class='btn btn-secondary fullscreen-btn' title='<?php echo $lang->retrack;?>'><i class='icon icon-fullscreen'></i><?php echo ' ' . $lang->retrack;?></button>
    <?php if(common::canBeChanged('testcase', $case)) common::printLink('testcase', 'create', "productID={$case->product}&branch={$case->branch}&moduleID={$case->module}", "<i class='icon icon-plus'></i> " . $lang->testcase->create, '', "class='btn btn-primary'"); ?>
  </div>
  <?php endif;?>
</div>

<div id="mainContent" class="main-row">
  <div class='main-col col-8'>
    <div class='cell' style='word-break:break-all'>
      <?php if($case->auto != 'unit' and !empty($case->precondition)):?>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testcase->precondition;?></div>
        <div class="detail-content article-content"><?php echo nl2br($case->precondition);?></div>
      </div>
      <?php endif;?>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testcase->steps;?></div>
        <div class="detail-content">
          <table class='table table-condensed table-hover table-striped table-bordered' id='steps'>
            <thead>
              <tr>
                <th class='w-50px'><?php echo $lang->testcase->stepID;?></th>
                <th class='w-p60 text-left'><?php echo $lang->testcase->stepDesc;?></th>
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
        </div>
      </div>
      <?php if(!empty($case->xml)):?>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testcase->xml;?></div>
        <div class="detail-content article-content"><?php echo nl2br(htmlSpecialString($case->xml));?></div>
      </div>
      <?php endif;?>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $case->files, 'fieldset' => 'true', 'object' => $case, 'method' => 'view', 'showDelete' => false));?>
    </div>
    <?php $this->printExtendFields($case, 'div', "position=left&inForm=0&inCell=1");?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php $case->isLibCase = $isLibCase;?>
        <?php $case->caseFails = $caseFails;?>
        <?php $case->runID     = $from == 'testcase' ? 0 : $run->id;?>
        <?php echo $this->testcase->buildOperateMenu($case, 'view');?>
      </div>
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
              <th><?php echo $lang->testcase->fromCase;?></th>
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
            <tr>
              <th class='thWidth'><?php echo $lang->testcase->lib;?></th>
              <td><?php echo common::hasPriv('caselib', 'browse') ? html::a($this->createLink('caselib', 'browse', "libID=$case->lib"), $libName) : $libName;?></td>
            </tr>
            <?php else:?>
            <tr class='<?php if($product->shadow) echo 'hide';?>'>
              <th class='thWidth'><?php echo $lang->testcase->product;?></th>
              <td><?php echo (common::hasPriv('product', 'browse') and $product->name) ? html::a($this->createLink('product', 'browse', "productID=$case->product"), $product->name) : $product->name;?></td>
            </tr>
            <?php if($product->type != 'normal'):?>
            <tr>
              <th><?php echo sprintf($lang->product->branch, $lang->product->branchName[$product->type]);?></th>
              <td><?php echo common::hasPriv('testcase', 'browse') ? html::a($this->createLink('testcase', 'browse', "productID=$case->product&branch=$case->branch"), $branchName) : $branchName;?></td>
            </tr>
            <?php endif;?>
            <?php endif;?>
            <tr>
              <th class='thWidth'><?php echo $lang->testcase->module;?></th>
              <td>
                <?php
                $tab = $this->app->tab;

                if(empty($modulePath))
                {
                    echo "/";
                }
                else
                {
                    if($caseModule->branch and isset($branches[$caseModule->branch]))
                    {
                        echo $branches[$caseModule->branch] . $lang->arrow;
                    }

                    foreach($modulePath as $key => $module)
                    {
                        if($tab == 'qa' || $tab == 'ops')
                        {
                            if($isLibCase)
                            {
                                if(!common::printLink('caselib', 'browse', "libID=$case->lib&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
                            }
                            else
                            {
                                if(!common::printLink('testcase', 'browse', "productID=$case->product&branch=$module->branch&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
                            }
                        }
                        if($tab == 'project' and !common::printLink('project', 'testcase', "projectID={$this->session->project}&productID=$case->product&branch=$module->branch&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
                        if($tab == 'execution' or $tab == 'product') echo $module->name;
                        if(isset($modulePath[$key + 1])) echo $lang->arrow;
                    }
                }
                ?>
              </td>
            </tr>
            <?php if(!$isLibCase):?>
            <tr class='nofixed'>
              <th><?php echo $lang->testcase->story;?></th>
              <td>
                <?php
                $class = isonlybody() ? 'showinonlybody' : 'iframe';
                $param = $tab == 'project' ? "&version=0&projectID={$this->session->project}" : '';
                if(isset($case->storyTitle))
                {
                    if(common::hasPriv('story', 'view'))
                    {
                        echo html::a($this->createLink('story', 'view', "storyID=$case->story" . $param, '', true), "#$case->story:$case->storyTitle", '', "class=$class data-width='80%'");
                    }
                    else
                    {
                        echo "#$case->story:$case->storyTitle";
                    }
                }
                if($case->story and $case->storyStatus == 'active' and $case->latestStoryVersion > $case->storyVersion)
                {
                    echo "(<span class='warning'>{$lang->story->changed}</span> ";
                    if(common::hasPriv('testcase', 'confirmStoryChange', $case)) echo html::a($this->createLink('testcase', 'confirmStoryChange', "caseID=$case->id"), $lang->confirm, 'hiddenwin');
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
              <td class='status-testcase status-<?php echo $case->status;?>'>
                <?php
                echo $this->processStatus('testcase', $case);
                if($case->version > $case->currentVersion and $from == 'testtask')
                {
                    echo "(<span class='warning' title={$lang->testcase->fromTesttask}>{$lang->testcase->changed}</span> ";
                    if(common::hasPriv('testcase', 'confirmchange')) echo html::a($this->createLink('testcase', 'confirmchange', "caseID=$case->id&taskID=$taskID"), $lang->testcase->sync, 'hiddenwin', "class='btn btn-mini btn-info'");
                    echo ")";
                }
                if(isset($case->fromCaseVersion) and $case->fromCaseVersion > $case->version and $from != 'testtask' and !empty($case->product))
                {
                    echo "(<span class='warning' title={$lang->testcase->fromCaselib}>{$lang->testcase->changed}</span> ";
                    if(common::hasPriv('testcase', 'confirmLibcaseChange')) echo html::a($this->createLink('testcase', 'confirmLibcaseChange', "caseID=$case->id&libcaseID=$case->fromCaseID"), $lang->testcase->sync, 'hiddenwin', "class='btn btn-mini btn-info'");
                    if(common::hasPriv('testcase', 'ignoreLibcaseChange'))  echo html::a($this->createLink('testcase', 'ignoreLibcaseChange', "caseID=$case->id"), $lang->testcase->ignore, 'hiddenwin', "class='btn btn-mini btn-info'");
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
              <td class='result-testcase <?php echo $case->lastRunResult;?>'><?php echo $case->lastRunResult ? $lang->testcase->resultList[$case->lastRunResult] : $lang->testcase->unexecuted;?></td>
            </tr>
            <?php endif;?>
            <tr>
              <th><?php echo $lang->testcase->keywords;?></th>
              <td><?php echo $case->keywords;?></td>
            </tr>
            <?php if(!$isLibCase):?>
            <tr>
              <th><?php echo $lang->testcase->linkCase;?></th>
              <td class='linkCaseTitles'>
                <?php
                if(isset($case->linkCaseTitles))
                {
                    foreach($case->linkCaseTitles as $linkCaseID => $linkCaseTitle)
                    {
                        echo html::a($this->createLink('testcase', 'view', "caseID=$linkCaseID", '', true), "#$linkCaseID $linkCaseTitle", '', "class='iframe' data-width='80%' title='$linkCaseTitle'") . '<br />';
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
              <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$case->fromBug", '', true), $case->fromBugData->title, '', "class='iframe' data-width='80%'");?></td>
            </tr>
            <?php endif;?>
            <?php if($case->toBugs):?>
            <tr>
              <td class='linkBugTitles'>
              <?php
              foreach($case->toBugs as $bugID => $bug)
              {
                  echo html::a($this->createLink('bug', 'view', "bugID=$bugID", '', true), "#$bugID " . $bug->title, '', "class='iframe' data-width='80%' title='$bug->title'") . '<br />';
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
              <th class='lifeThWidth'><?php echo $lang->testcase->openedBy;?></th>
              <td><?php echo zget($users, $case->openedBy) . $lang->at . $case->openedDate;?></td>
            </tr>
            <?php if($config->testcase->needReview or !empty($config->testcase->forceReview)):?>
            <tr>
              <th><?php echo $lang->testcase->reviewedBy;?></th>
              <td><?php $reviewedBy = explode(',', $case->reviewedBy); foreach($reviewedBy as $account) echo ' ' . zget($users, trim($account)); ?></td>
            </tr>
            <tr>
              <th><?php echo $lang->testcase->reviewedDate;?></th>
              <td><?php if($case->reviewedBy) echo $case->reviewedDate;?></td>
            </tr>
            <?php endif;?>
            <tr>
              <th><?php echo $lang->testcase->lblLastEdited;?></th>
              <td><?php if($case->lastEditedBy) echo zget($users, $case->lastEditedBy) . $lang->at . $case->lastEditedDate;?></td>
            </tr>
          </table>
        </div>
      </details>
    </div>
    <?php $this->printExtendFields($case, 'div', "position=right&inForm=0&inCell=1");?>
    <div class='cell'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>
<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext, $this->createLink('testcase', 'view', "caseID=%s&version=&from=$from&taskID=$taskID"));?>
</div>
<?php
js::set('fullscreen', $lang->fullscreen);
js::set('retrack', $lang->retrack);
js::set('isLibCase', $isLibCase);
?>
<?php include '../../common/view/footer.html.php';?>
