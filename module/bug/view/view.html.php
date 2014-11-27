<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: view.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['bug']);?> <strong><?php echo $bug->id;?></strong></span>
    <strong><?php echo $bug->title;?></strong>
    <?php if($bug->deleted):?>
    <span class='label label-danger'><?php echo $lang->bug->deleted;?></span>
    <?php endif; ?>
  </div>
  <div class='actions'>
    <?php
    $browseLink    = $app->session->bugList != false ? $app->session->bugList : inlink('browse', "productID=$bug->product");
    $params        = "bugID=$bug->id";
    $copyParams    = "productID=$productID&extras=bugID=$bug->id";
    $convertParams = "productID=$productID&moduleID=0&from=bug&bugID=$bug->id";
    if(!$bug->deleted)
    {
        ob_start();
        echo "<div class='btn-group'>";
        common::printIcon('bug', 'confirmBug', $params, $bug, 'button', 'search', '', 'iframe', true);
        common::printIcon('bug', 'assignTo',   $params, '',   'button', '', '', 'iframe', true);
        common::printIcon('bug', 'resolve',    $params, $bug, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('bug', 'close',      $params, $bug, 'button', '', '', 'text-danger iframe showinonlybody', true);
        common::printIcon('bug', 'activate',   $params, $bug, 'button', '', '', 'text-success iframe showinonlybody', true);

        common::printIcon('bug', 'toStory', "product=$bug->product&module=0&story=0&project=0&bugID=$bug->id", $bug, 'button', $lang->icons['story']);
        common::printIcon('bug', 'createCase', $convertParams, '', 'button', 'sitemap');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printIcon('bug', 'edit', $params);
        common::printCommentIcon('bug');
        common::printIcon('bug', 'create', $copyParams, '', 'button', 'copy');
        common::printIcon('bug', 'delete', $params, '', 'button', '', 'hiddenwin');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printRPN($browseLink, $preAndNext);
        echo '</div>';

        $actionLinks = ob_get_contents();
        ob_end_clean();
        echo $actionLinks;
    }
    else
    {
        common::printRPN($browseLink);
    }
    ?>
  </div>
</div>

<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <fieldset>
        <legend><?php echo $lang->bug->legendSteps;?></legend>
        <div class='content'><?php echo str_replace('<p>[', '<p class="stepTitle">[', $bug->steps);?></div>
      </fieldset>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $bug->files, 'fieldset' => 'true'));?>
      <?php include '../../common/view/action.html.php';?>
      <div class='actions'><?php if(!$bug->deleted) echo $actionLinks;?></div>
      <fieldset id='commentBox' class='hide'>
        <legend><?php echo $lang->comment;?></legend>
        <form method='post' action='<?php echo inlink('edit', "bugID=$bug->id&comment=true")?>'>
          <div class="form-group"><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></div>
          <?php echo html::submitButton() . html::backButton();?>
        </form>
      </fieldset>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendBasicInfo' data-toggle='tab'><?php echo $lang->bug->legendBasicInfo;?></a></li>
          <li><a href='#legendPrjStoryTask' data-toggle='tab'><?php echo $lang->bug->legendPrjStoryTask;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendBasicInfo'>
            <table class='table table-data table-condensed table-borderless table-fixed'>
              <tr valign='middle'>
                <th class='w-60px'><?php echo $lang->bug->product;?></th>
                <td><?php if(!common::printLink('bug', 'browse', "productID=$bug->product", $productName)) echo $productName;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->module;?></th>
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
                         if(!common::printLink('bug', 'browse', "productID=$bug->product&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
                         if(isset($modulePath[$key + 1])) echo $lang->arrow;
                     }
                  }
                  ?>
                </td>
              </tr>
              <tr valign='middle'>
                <th><?php echo $lang->bug->productplan;?></th>
                <td><?php if(!$bug->plan or !common::printLink('productplan', 'linkBug', "planID=$bug->plan", $bug->planName)) echo $bug->planName;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->type;?></th>
                <td><?php if(isset($lang->bug->typeList[$bug->type])) echo $lang->bug->typeList[$bug->type]; else echo $bug->type;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->severity;?></th>
                <td><strong><?php echo $lang->bug->severityList[$bug->severity];?></strong></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->pri;?></th>
                <td><strong><?php echo $lang->bug->priList[$bug->pri];?></strong></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->status;?></th>
                <td class='bug-<?php echo $bug->status?>'><strong><?php echo $lang->bug->statusList[$bug->status];?></strong></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->activatedCount;?></th>
                <td><?php echo $bug->activatedCount;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->confirmed;?></th>
                <td><?php echo $lang->bug->confirmedList[$bug->confirmed];?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->lblAssignedTo;?></th>
                <td><?php if($bug->assignedTo) echo $users[$bug->assignedTo] . $lang->at . $bug->assignedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->os;?></th>
                <td><?php echo $lang->bug->osList[$bug->os];?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->browser;?></th>
                <td><?php echo $lang->bug->browserList[$bug->browser];?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->keywords;?></th>
                <td><?php echo $bug->keywords;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->mailto;?></th>
                <td><?php $mailto = explode(',', str_replace(' ', '', $bug->mailto)); foreach($mailto as $account) echo ' ' . $users[$account]; ?></td>
              </tr>
            </table>
          </div>
          <div class='tab-pane' id='legendPrjStoryTask'>
            <table class='table table-data table-condensed table-borderless table-fixed'>
              <tr>
                <th class='w-60px'><?php echo $lang->bug->project;?></th>
                <td><?php if($bug->project) echo html::a($this->createLink('project', 'browse', "projectid=$bug->project"), $bug->projectName);?></td>
              </tr>
              <tr class='nofixed'>
                <th><?php echo $lang->bug->story;?></th>
                <td>
                  <?php
                  if($bug->story) echo html::a($this->createLink('story', 'view', "storyID=$bug->story"), "#$bug->story $bug->storyTitle");
                  if($bug->storyStatus == 'active' and $bug->latestStoryVersion > $bug->storyVersion)
                  {
                      echo "(<span class='warning'>{$lang->story->changed}</span> ";
                      echo html::a($this->createLink('bug', 'confirmStoryChange', "bugID=$bug->id"), $lang->confirm, 'hiddenwin');
                      echo ")";
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->task;?></th>
                <td><?php if($bug->task) echo html::a($this->createLink('task', 'view', "taskID=$bug->task"), $bug->taskName);?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendLife' data-toggle='tab'><?php echo $lang->bug->legendLife;?></a></li>
          <li><a href='#legendMisc' data-toggle='tab'><?php echo $lang->bug->legendMisc;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendLife'>
            <table class='table table-data table-condensed table-borderless table-fixed'>
              <tr>
                <th class='w-60px'><?php echo $lang->bug->openedBy;?></th>
                <td> <?php echo $users[$bug->openedBy] . $lang->at . $bug->openedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->openedBuild;?></th>
                <td>
                  <?php
                  if($bug->openedBuild)
                  {
                      $openedBuilds = explode(',', $bug->openedBuild);
                      foreach($openedBuilds as $openedBuild) isset($builds[$openedBuild]) ? print($builds[$openedBuild] . '<br />') : print($openedBuild . '<br />');
                  }
                  else
                  {
                      echo $bug->openedBuild;
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->lblResolved;?></th>
                <td><?php if($bug->resolvedBy) echo $users[$bug->resolvedBy] . $lang->at . $bug->resolvedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->resolvedBuild;?></th>
                <td><?php if(isset($builds[$bug->resolvedBuild])) echo $builds[$bug->resolvedBuild]; else echo $bug->resolvedBuild;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->resolution;?></th>
                <td>
                  <?php
                  echo $lang->bug->resolutionList[$bug->resolution];
                  if(isset($bug->duplicateBugTitle)) echo " #$bug->duplicateBug:" . html::a($this->createLink('bug', 'view', "bugID=$bug->duplicateBug"), $bug->duplicateBugTitle);
                  ?>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->closedBy;?></th>
                <td><?php if($bug->closedBy) echo $users[$bug->closedBy] . $lang->at . $bug->closedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->lblLastEdited;?></th>
                <td><?php if($bug->lastEditedBy) echo zget($users, $bug->lastEditedBy, $bug->lastEditedBy) . $lang->at . $bug->lastEditedDate?></td>
              </tr>
            </table>
          </div>
          <div class='tab-pane' id='legendMisc'>
            <table class='table table-data table-condensed table-borderless table-fixed'>
              <tr>
                <th class='w-60px'><?php echo $lang->bug->fromCase;?></th>
                <td><?php if($bug->case) echo html::a($this->createLink('testcase', 'view', "caseID=$bug->case"), $bug->caseTitle);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->toCase;?></th>
                <td>
                <?php 
                foreach($bug->toCases as $caseID => $case) 
                {
                    echo '<p style="margin-bottom:0;">' . html::a($this->createLink('testcase', 'view', "caseID=$caseID"), $case) . '</p>';
                }
                ?>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->linkBug;?></th>
                <td>
                  <?php
                  if(isset($bug->linkBugTitles))
                  {
                      foreach($bug->linkBugTitles as $linkBugID => $linkBugTitle)
                      {
                          echo html::a($this->createLink('bug', 'view', "bugID=$linkBugID"), "#$linkBugID $linkBugTitle", '_blank') . '<br />';
                      }
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->case;?></th>
                <td><?php if(isset($bug->caseTitle)) echo html::a($this->createLink('testcase', 'view', "caseID=$bug->case"), "#$bug->case $bug->caseTitle", '_blank');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->toStory;?></th>
                <td><?php if($bug->toStory != 0) echo html::a($this->createLink('story', 'view', "storyID=$bug->toStory"), "#$bug->toStory $bug->toStoryTitle", '_blank');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->bug->toTask;?></th>
                <td><?php if($bug->toTask != 0) echo html::a($this->createLink('task', 'view', "taskID=$bug->toTask"), "#$bug->toTask $bug->toTaskTitle", '_blank');?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
