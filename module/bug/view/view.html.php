<?php
/**
 * The view file of bug module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../file/view/download.html.php';?>
<div class='yui-d0'>
  <div id='titlebar'>
    <div id='main' <?php if($bug->deleted) echo "class='deleted'";?>>BUG #<?php echo $bug->id . $lang->colon . $bug->title;?></div>
    <div>
      <?php
      $browseLink = $app->session->bugList != false ? $app->session->bugList : inlink('browse', "productID=$bug->product");
      $params     = "bugID=$bug->id";
      $copyParams = "productID=$productID&extra=bugID=$bug->id";
      if(!$bug->deleted)
      {
          common::printLink('bug', 'edit', $params, $lang->bug->buttonEdit);
          if(!($bug->status == 'active'   and common::printLink('bug', 'resolve', $params, $lang->bug->buttonResolve)))   echo $lang->bug->buttonResolve . ' ';
          if(!($bug->status == 'resolved' and common::printLink('bug', 'close', $params, $lang->bug->buttonClose)))       echo $lang->bug->buttonClose . ' ';
          if(!(($bug->status == 'closed' or $bug->status == 'resolved') and common::printLink('bug', 'activate', $params, $lang->bug->buttonActivate))) echo $lang->bug->buttonActivate . ' ';
          common::printLink('bug', 'create', $copyParams, $lang->bug->buttonCopy);
          common::printLink('bug', 'delete', $params, $lang->delete, 'hiddenwin');
      }
      echo html::a($browseLink, $lang->goback);
      ?>
    </div>
  </div>
</div>

<div class='yui-d0 yui-t8'>
  <div class='yui-main'>
    <div class='yui-b'>
      <fieldset>
        <legend><?php echo $lang->bug->legendSteps;?></legend>
        <div class='content'><?php echo $bug->steps;?></div>
      </fieldset>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $bug->files, 'fieldset' => 'true'));?>
      <?php include '../../common/view/action.html.php';?>
      <div class='a-center' style='font-size:16px; font-weight:bold'>
      <?php
      if(!$bug->deleted)
      {
          common::printLink('bug', 'edit', $params, $lang->bug->buttonEdit);
          if(!($bug->status == 'active'   and common::printLink('bug', 'resolve', $params, $lang->bug->buttonResolve)))   echo $lang->bug->buttonResolve . ' ';
          if(!($bug->status == 'resolved' and common::printLink('bug', 'close', $params, $lang->bug->buttonClose)))       echo $lang->bug->buttonClose . ' ';
          if(!(($bug->status == 'closed' or $bug->status == 'resolved') and common::printLink('bug', 'activate', $params, $lang->bug->buttonActivate))) echo $lang->bug->buttonActivate . ' ';
          common::printLink('bug', 'create', $copyParams, $lang->bug->buttonCopy);
          common::printLink('bug', 'delete', $params, $lang->delete, 'hiddenwin');
      }
      echo html::a($browseLink, $lang->goback);
      ?>
      </div>
    </div>
  </div>

  <div class='yui-b'>
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
          <th class='rowhead'><?php echo $lang->bug->openedBy;?></th>
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
          <td class='rowhead'><?php echo $lang->bug->project;?></td>
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
          <td class='rowhead'><?php echo $lang->bug->mailto;?></td>
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
      </table>
    </fieldset>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
