<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div id='main' <?php if($bug->deleted) echo "class='deleted'";?>>BUG #<?php echo $bug->id . $lang->colon . $bug->title;?></div>
  <div>
    <?php
    $browseLink    = $app->session->bugList != false ? $app->session->bugList : inlink('browse', "productID=$bug->product");
    $params        = "bugID=$bug->id";
    $copyParams    = "productID=$productID&extras=bugID=$bug->id";
    $convertParams = "productID=$productID&moduleID=0&from=bug&bugID=$bug->id";
    if(!$bug->deleted)
    {
        ob_start();
        if($this->bug->isClickable($bug, 'confirmBug')) common::printLink('bug', 'confirmBug', $params, $lang->bug->buttonConfirm);
        common::printLink('bug', 'assignTo', $params, $lang->bug->buttonAssign);
        if($this->bug->isClickable($bug, 'resolve'))  common::printLink('bug', 'resolve', $params, $lang->bug->buttonResolve);
        if($this->bug->isClickable($bug, 'close'))    common::printLink('bug', 'close', $params, $lang->bug->buttonClose);
        if($this->bug->isClickable($bug, 'activate')) common::printLink('bug', 'activate', $params, $lang->bug->buttonActivate);

        if($this->bug->isClickable($bug, 'toStory'))
        {
            common::printSplitIcon();
            common::printLink('story', 'create', "product=$bug->product&module=0&story=0&project=0&bugID=$bug->id", $lang->bug->toStory);
        }
        common::printLink('testcase', 'create', $convertParams, $lang->bug->buttonCreateTestcase);

        if($this->bug->isClickable($bug, 'edit')) common::printSplitIcon();
        common::printIcon('bug', 'edit', $params, '', 'big');
        common::printCommentIcon('bug');
        common::printIcon('bug', 'create', $copyParams, '', 'big', 'copy');
        common::printIcon('bug', 'delete', $params, '', 'big', '', 'hiddenwin');

        if($this->bug->isClickable($bug, 'goback')) common::printSplitIcon();
        common::printRPN($browseLink, $preAndNext);

        $actionLinks = ob_get_contents();
        ob_end_clean();
        echo $actionLinks;
    }
    ?>
  </div>
</div>

<table class='cont-rt5'>
  <tr valign='top'>
    <td>
      <fieldset>
        <legend><?php echo $lang->bug->legendSteps;?></legend>
        <div class='content'><?php echo str_replace('<p>[', '<p class="stepTitle">[', $bug->steps);?></div>
      </fieldset>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $bug->files, 'fieldset' => 'true'));?>
      <?php include '../../common/view/action.html.php';?>
      <div class='a-center actionlink'><?php if(!$bug->deleted) echo $actionLinks;?></div>
      <div id='comment' class='hidden'>
        <fieldset>
          <legend><?php echo $lang->comment;?></legend>
          <form method='post' action='<?php echo inlink('edit', "bugID=$bug->id&comment=true")?>'>
            <table align='center' class='table-1'>
            <tr><td><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></td></tr>
            <tr><td><?php echo html::submitButton() . html::resetButton();?></td></tr>
            </table>
          </form>
        </fieldset>
      </div>
    </td>
    <td class='divider'></td>
    <td class='side'>
      <fieldset>
        <legend><?php echo $lang->bug->legendBasicInfo;?></legend>
        <table class='table-1 a-left'>
          <tr valign='middle'>
            <th class='rowhead'><?php echo $lang->bug->product;?></th>
            <td><?php if(!common::printLink('bug', 'browse', "productID=$bug->product", $productName)) echo $productName;?>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->bug->module;?></th>
            <td> 
<?php
        foreach($modulePath as $key => $module)
        {
            if(!common::printLink('bug', 'browse', "productID=$bug->product&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
            if(isset($modulePath[$key + 1])) echo $lang->arrow;
        }
?>
            </td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->type;?></td>
            <td><?php if(isset($lang->bug->typeList[$bug->type])) echo $lang->bug->typeList[$bug->type]; else echo $bug->type;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->severity;?></td>
            <td><strong><?php echo $lang->bug->severityList[$bug->severity];?></strong></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->pri;?></td>
            <td><strong><?php echo $lang->bug->priList[$bug->pri];?></strong></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->status;?></td>
            <td><strong><?php echo $lang->bug->statusList[$bug->status];?></strong></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->activatedCount;?></td>
            <td><?php echo $bug->activatedCount;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->confirmed;?></td>
            <td><?php echo $lang->bug->confirmedList[$bug->confirmed];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->lblAssignedTo;?></td>
            <td><?php if($bug->assignedTo) echo $users[$bug->assignedTo] . $lang->at . $bug->assignedDate;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->os;?></td>
            <td><?php echo $lang->bug->osList[$bug->os];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->browser;?></td>
            <td><?php echo $lang->bug->browserList[$bug->browser];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->keywords;?></td>
            <td><?php echo $bug->keywords;?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->bug->legendLife;?></legend>
        <table class='table-1 a-left fixed'>
          <tr>
            <th class='rowhead w-p20'><?php echo $lang->bug->openedBy;?></th>
            <td> <?php echo $users[$bug->openedBy] . $lang->at . $bug->openedDate;?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->bug->openedBuild;?></th>
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
            <th class='rowhead'><?php echo $lang->bug->lblResolved;?></th>
            <td><?php if($bug->resolvedBy) echo $users[$bug->resolvedBy] . $lang->at . $bug->resolvedDate;?>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->bug->resolvedBuild;?></th>
            <td><?php if(isset($builds[$bug->resolvedBuild])) echo $builds[$bug->resolvedBuild]; else echo $bug->resolvedBuild;?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->bug->resolution;?></th>
            <td>
<?php
        echo $lang->bug->resolutionList[$bug->resolution];
        if(isset($bug->duplicateBugTitle)) echo " #$bug->duplicateBug:" . html::a($this->createLink('bug', 'view', "bugID=$bug->duplicateBug"), $bug->duplicateBugTitle);
?>
            </td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->bug->closedBy;?></th>
            <td><?php if($bug->closedBy) echo $users[$bug->closedBy] . $lang->at . $bug->closedDate;?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->bug->lblLastEdited;?></th>
            <td><?php if($bug->lastEditedBy) echo $users[$bug->lastEditedBy] . $lang->at . $bug->lastEditedDate?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendPrjStoryTask;?></legend>
        <table class='table-1 a-left fixed'>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->bug->project;?></td>
            <td><?php if($bug->project) echo html::a($this->createLink('project', 'browse', "projectid=$bug->project"), $bug->projectName);?></td>
          </tr>
          <tr class='nofixed'>
            <td class='rowhead'><?php echo $lang->bug->story;?></td>
            <td>
<?php
        if($bug->story) echo html::a($this->createLink('story', 'view', "storyID=$bug->story"), $bug->storyTitle);
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
            <td class='rowhead'><?php echo $lang->bug->task;?></td>
            <td><?php if($bug->task) echo html::a($this->createLink('task', 'view', "taskID=$bug->task"), $bug->taskName);?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->bug->legendMisc;?></legend>
        <table class='table-1 a-left fixed'>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->bug->mailto;?></td>
            <td><?php $mailto = explode(',', str_replace(' ', '', $bug->mailto)); foreach($mailto as $account) echo ' ' . $users[$account]; ?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->linkBug;?></td>
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
            <td class='rowhead'><?php echo $lang->bug->case;?></td>
            <td><?php if(isset($bug->caseTitle)) echo html::a($this->createLink('testcase', 'view', "caseID=$bug->case"), "#$bug->case $bug->caseTitle", '_blank');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->toStory;?></td>
            <td><?php if($bug->toStory != 0) echo html::a($this->createLink('story', 'view', "storyID=$bug->toStory"), "#$bug->toStory $bug->toStoryTitle", '_blank');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->toTask;?></td>
            <td><?php if($bug->toTask != 0) echo html::a($this->createLink('task', 'view', "taskID=$bug->toTask"), "#$bug->toTask $bug->toTaskTitle", '_blank');?></td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
