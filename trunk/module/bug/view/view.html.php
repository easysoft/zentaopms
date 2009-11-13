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
    if(common::hasPriv('bug', 'edit')) echo html::a($this->createLink('bug', 'edit',     "bugID=$bug->id"), $lang->bug->buttonEdit);
    if(common::hasPriv('bug', 'resolve')  and $bug->status == 'active')   echo html::a($this->createLink('bug', 'resolve',  "bugID=$bug->id"), $lang->bug->buttonResolve); else echo $lang->bug->buttonResolve . ' ';
    if(common::hasPriv('bug', 'close')    and $bug->status == 'resolved') echo html::a($this->createLink('bug', 'close',    "bugID=$bug->id"), $lang->bug->buttonClose); else echo $lang->bug->buttonClose . ' ';
    if(common::hasPriv('bug', 'activate') and ($bug->status == 'closed' or $bug->status == 'resolved')) echo html::a($this->createLink('bug', 'activate', "bugID=$bug->id"), $lang->bug->buttonActivate); else echo $lang->bug->buttonActivate . ' ';
    if(common::hasPriv('bug', 'browse'))   echo html::a($this->session->bugList, $lang->bug->buttonToList);
    ?>
    </div>
  </div>
</div>

<div class='yui-doc3 yui-t7'>
  <div class='yui-g'>  

    <div class='yui-u first'>  
      <fieldset>
        <legend><?php echo $lang->bug->legendBasicInfo;?></legend>
        <table class='table-1 a-left' cellpadding='0' cellspacing='0'>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->labProductAndModule;?></td>
            <td>
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
            <td><?php echo $lang->bug->typeList->{$bug->type};?></td>
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
            <td class='rowhead'><?php echo $lang->bug->assignedTo;?></td>
            <td><?php echo $users[$bug->assignedTo];?></td>
          </tr>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->bug->assignedDate;?></td>
            <td><?php echo $bug->assignedDate;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->lastEditedBy;?></td>
            <td><?php echo $users[$bug->lastEditedBy];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->lastEditedDate;?></td>
            <td><?php echo $bug->lastEditedDate;?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendPrjStoryTask;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->project;?></td>
            <td><?php echo $bug->projectName;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->story;?></td>
            <td><?php echo $bug->storyTitle;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->task;?></td>
            <td><?php echo $bug->taskName;?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendMailto;?></legend>
        <div><?php $mailto = explode(',', $bug->mailto); foreach($mailto as $account) echo ' ' . $users[$account]; ?></div>
      </fieldset>

      <fieldset>
      <legend><?php echo $lang->bug->legendAttatch;?></legend>
        <div>&nbsp;</div>
      </fieldset>
      
    </div>  

    <div class='yui-u'>  
      <fieldset>
        <legend><?php echo $lang->bug->legendOpenInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->bug->openedBy;?></td>
            <td><?php echo $users[$bug->openedBy];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->openedDate;?></td>
            <td><?php echo $bug->openedDate;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->openedBuild;?></td>
            <td><?php echo $bug->openedBuild;?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendResolveInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->bug->resolvedBy;?></td>
            <td><?php echo $users[$bug->resolvedBy];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolvedDate;?></td>
            <td><?php echo $bug->resolvedDate;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolvedBuild;?></td>
            <td><?php echo $bug->resolvedBuild;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolution;?></td>
            <td>
              <?php 
              echo $lang->bug->resolutionList[$bug->resolution];
              if(isset($bug->duplicateBugTitle)) echo " #$bug->duplicateBug:" . html::a($this->createLink('bug', 'view', "bugID=$bug->duplicateBug"), $bug->duplicateBugTitle);
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendCloseInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->bug->closedBy;?></td>
            <td><?php echo $users[$bug->closedBy];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->closedDate;?></td>
            <td><?php echo $bug->closedDate;?></td>
          </tr>
        </table>
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
</div>  

<div class='yui-d0'>
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
  <fieldset>
    <legend><?php echo $lang->bug->legendSteps;?></legend>
    <div class='content'><?php echo nl2br($bug->steps);?></div>
  </fieldset>
  <fieldset>
    <legend><?php echo $lang->bug->legendHistory;?></legend>
    <ol>
      <?php foreach($actions as $action):?>
      <li>
        <span><?php echo "$action->date, <strong>$action->action</strong> BY <strong>$action->actor</strong>"; ?></span>
        <?php if(!empty($action->comment) or !empty($action->history)):?>
        <div class='history'>
        <?php
        foreach($action->history as $history)
        {
            echo "CHANGE <strong>$history->field</strong> FROM '$history->old' TO '$history->new' . <br />";
        }
        echo nl2br($action->comment); 
        ?>
        </div>
        <?php endif;?>
      </li>
      <?php endforeach;?>
    </ol>
  </fieldset>

</div>
<?php include '../../common/footer.html.php';?>
