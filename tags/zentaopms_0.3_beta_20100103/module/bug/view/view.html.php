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
    common::printLink('bug', 'edit', "bugID=$bug->id", $lang->bug->buttonEdit);
    if($bug->status == 'active')   common::printLink('bug', 'resolve',  "bugID=$bug->id", $lang->bug->buttonResolve); else echo $lang->bug->buttonResolve . ' ';
    if($bug->status == 'resolved') common::printLink('bug', 'close',    "bugID=$bug->id", $lang->bug->buttonClose);  else echo $lang->bug->buttonClose . ' ';
    if($bug->status == 'closed' or $bug->status == 'resolved') common::printLink('bug', 'activate', "bugID=$bug->id", $lang->bug->buttonActivate); else echo $lang->bug->buttonActivate . ' ';
    if(common::hasPriv('bug', 'browse'))   echo html::a($this->session->bugList, $lang->bug->buttonToList);
    ?>
    </div>
  </div>
</div>

<div class='yui-d0 yui-t6'>
  <div class='yui-main'>
    <div class='yui-b'>
      <fieldset>
        <legend><?php echo $lang->bug->legendSteps;?></legend>
        <div class='content'><?php echo nl2br($bug->steps);?></div>
      </fieldset>
      <?php include '../../common/action.html.php';?>
      <fieldset>
        <legend><?php echo $lang->bug->legendAction;?></legend>
        <div class='a-center' style='font-size:16px; font-weight:bold'>
        <?php
        if(common::hasPriv('bug', 'edit')) echo html::a($this->createLink('bug', 'edit',     "bugID=$bug->id"), $lang->bug->buttonEdit);
        if(common::hasPriv('bug', 'resolve')  and $bug->status == 'active')   echo html::a($this->createLink('bug', 'resolve',  "bugID=$bug->id"), $lang->bug->buttonResolve); else echo $lang->bug->buttonResolve . ' ';
        if(common::hasPriv('bug', 'close')    and $bug->status == 'resolved') echo html::a($this->createLink('bug', 'close',    "bugID=$bug->id"), $lang->bug->buttonClose); else echo $lang->bug->buttonClose . ' ';
        if(common::hasPriv('bug', 'activate') and ($bug->status == 'closed' or $bug->status == 'resolved')) echo html::a($this->createLink('bug', 'activate', "bugID=$bug->id"), $lang->bug->buttonActivate); else echo $lang->bug->buttonActivate . ' ';
        if(common::hasPriv('bug', 'browse'))   echo html::a($this->session->bugList, $lang->bug->buttonToList);
        ?>
        </div>
      </fieldset>
    </div>
  </div>

  <div class='yui-b'>
    <fieldset>
      <legend><?php echo $lang->bug->legendBasicInfo;?></legend>
      <table class='table-1 a-left fixed'>
        <tr>
          <th class='w-p25 rowhead'><?php echo $lang->bug->labProductAndModule;?></th>
          <td class='nobr'>
            <?php
            echo $productName;
            if(!empty($modulePath)) echo $lang->arrow;
            foreach($modulePath as $key => $module)
            {
                echo $module->name;
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
          <td class='rowhead'><?php echo $lang->bug->os;?></td>
          <td><?php echo $lang->bug->osList->{$bug->os};?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->severity;?></td>
          <td><?php echo $bug->severity;?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->status;?></td>
          <td><?php echo $bug->status;?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->labAssignedTo;?></td>
          <td><?php if($bug->assignedTo) echo $users[$bug->assignedTo] . $lang->at . $bug->assignedDate;?></td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend><?php echo $lang->bug->legendLife;?></legend>
      <table class='table-1 a-left fixed'>
        <tr>
          <th class='rowhead w-p25'><?php echo $lang->bug->openedBy;?></th>
          <td> <?php echo $users[$bug->openedBy] . $lang->at . $bug->openedDate;?></td>
        </tr>
        <tr>
          <th class='rowhead'><?php echo $lang->bug->openedBuild;?></th>
          <td><?php echo $bug->openedBuild;?></td>
        </tr>
        <tr>
          <th class='rowhead'><?php echo $lang->bug->labResolved;?></th>
          <td><?php if($bug->resolvedBy) echo $users[$bug->resolvedBy] . $lang->at . $bug->resolvedDate;?>
        </tr>
        <tr>
          <th class='rowhead'><?php echo $lang->bug->resolvedBuild;?></th>
          <td><?php echo $bug->resolvedBuild;?></td>
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
          <th class='rowhead'><?php echo $lang->bug->labLastEdited;?></th>
          <td><?php if($bug->lastEditedBy) echo $users[$bug->lastEditedBy] . $lang->at . $bug->lastEditedDate?></td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend><?php echo $lang->bug->legendPrjStoryTask;?></legend>
      <table class='table-1 a-left fixed'>
        <tr>
          <td class='rowhead w-p25'><?php echo $lang->bug->project;?></td>
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
      <legend><?php echo $lang->bug->legendMailto;?></legend>
      <div><?php $mailto = explode(',', $bug->mailto); foreach($mailto as $account) echo ' ' . $users[$account]; ?></div>
    </fieldset>

    <fieldset>
    <legend><?php echo $lang->bug->legendAttatch;?></legend>
      <div>
        <?php 
        foreach($bug->files as $file) echo html::a($file->fullPath, $file->title, '_blank');
        ?>
      </div>
    </fieldset>


    <fieldset>
      <legend><?php echo $lang->bug->legendLinkBugs;?></legend>
      <div>&nbsp;</div>
    </fieldset>

    <fieldset>
      <legend><?php echo $lang->bug->legendCases;?></legend>
      <div>&nbsp;</div>
    </fieldset>


  </div>
</div>

<?php include '../../common/footer.html.php';?>
