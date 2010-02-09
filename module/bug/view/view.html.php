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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>

<div class='yui-d0'>
  <div id='titlebar'>
    <div id='main'>BUG #<?php echo $bug->id . $lang->colon . $bug->title;?></div>
    <div>
      <?php
      $params = "bugID=$bug->id";
      common::printLink('bug', 'edit', $params, $lang->bug->buttonEdit);
      if(!($bug->status == 'active'   and common::printLink('bug', 'resolve', $params, $lang->bug->buttonResolve)))   echo $lang->bug->buttonResolve . ' ';
      if(!($bug->status == 'resolved' and common::printLink('bug', 'close', $params, $lang->bug->buttonClose)))       echo $lang->bug->buttonClose . ' ';
      if(!(($bug->status == 'closed' or $bug->status == 'resolved') and common::printLink('bug', 'activate', $params, $lang->bug->buttonActivate))) echo $lang->bug->buttonActivate . ' ';
      common::printLink('bug', 'browse', '', $lang->bug->buttonToList);
      ?>
    </div>
  </div>
</div>

<div class='yui-d0 yui-t8'>
  <div class='yui-main'>
    <div class='yui-b'>
      <fieldset>
        <legend><?php echo $lang->bug->legendSteps;?></legend>
        <div class='content'><?php echo nl2br($bug->steps);?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->bug->legendAttatch;?></legend>
        <div><?php foreach($bug->files as $file) echo html::a($file->fullPath, $file->title, '_blank');?></div>
      </fieldset>
      <?php include '../../common/action.html.php';?>
      <div class='a-center' style='font-size:16px; font-weight:bold'>
      <?php
      common::printLink('bug', 'edit', $params, $lang->bug->buttonEdit);
      if(!($bug->status == 'active'   and common::printLink('bug', 'resolve', $params, $lang->bug->buttonResolve)))   echo $lang->bug->buttonResolve . ' ';
      if(!($bug->status == 'resolved' and common::printLink('bug', 'close', $params, $lang->bug->buttonClose)))       echo $lang->bug->buttonClose . ' ';
      if(!(($bug->status == 'closed' or $bug->status == 'resolved') and common::printLink('bug', 'activate', $params, $lang->bug->buttonActivate))) echo $lang->bug->buttonActivate . ' ';
      common::printLink('bug', 'browse', '', $lang->bug->buttonToList);
      ?>
      </div>
    </div>
  </div>

  <div class='yui-b'>
    <fieldset>
      <legend><?php echo $lang->bug->legendBasicInfo;?></legend>
      <table class='table-1 a-left'>
        <tr valign='middle'>
          <th class='w-p20 rowhead'><?php echo $lang->bug->lblProductAndModule;?></th>
          <td>
            <?php
            if(!common::printLink('bug', 'browse', "productID=$bug->product", $productName)) echo $productName;
            if(!empty($modulePath)) echo $lang->arrow;
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
          <td><?php echo $lang->bug->typeList[$bug->type];?></td>
        </tr>

        <tr>
          <td class='rowhead'><?php echo $lang->bug->severity;?></td>
          <td><strong><?php echo $bug->severity;?></strong></td>
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
          <td><?php echo $builds[$bug->openedBuild];?></td>
        </tr>
        <tr>
          <th class='rowhead'><?php echo $lang->bug->lblResolved;?></th>
          <td><?php if($bug->resolvedBy) echo $users[$bug->resolvedBy] . $lang->at . $bug->resolvedDate;?>
        </tr>
        <tr>
          <th class='rowhead'><?php echo $lang->bug->resolvedBuild;?></th>
          <td><?php echo $builds[$bug->resolvedBuild];?></td>
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
        <tr>
          <td class='rowhead'><?php echo $lang->bug->story;?></td>
          <td><?php if($bug->story) echo html::a($this->createLink('story', 'view', "storyID=$bug->story"), $bug->storyTitle);?></td>
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
          <td><?php $mailto = explode(',', $bug->mailto); foreach($mailto as $account) echo ' ' . $users[$account]; ?></td>
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
<?php include '../../common/footer.html.php';?>
