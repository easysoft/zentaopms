<?php
/**
 * The edit file of bug module of ZenTaoPMS.
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
<?php include '../../common/view/chosen.html.php';?>
<?php include '../../common/view/autocomplete.html.php';?>
<?php include '../../common/view/alert.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<script language='Javascript'>
changeProductConfirmed = false;
changeProjectConfirmed = false;
confirmChangeProduct   = '<?php echo $lang->bug->confirmChangeProduct;?>';
oldProjectID           = '<?php echo $bug->project;?>';
oldStoryID             = '<?php echo $bug->story;?>';
oldTaskID              = '<?php echo $bug->task;?>';
oldOpenedBuild         = '<?php echo $bug->openedBuild;?>';
oldResolvedBuild       = '<?php echo $bug->resolvedBuild;?>';
emptySelect            = "<select name='task' id='task'><option value=''></option></select>";
userList               = "<?php echo join(',', array_keys($users));?>".split(',');
</script>
<form method='post' target='hiddenwin' enctype='multipart/form-data'>
<div id='titlebar'>
  <div id='main'>
  BUG #<?php echo $bug->id . $lang->colon;?>
  <?php echo html::input('title', str_replace("'","&#039;",$bug->title), 'class=text-1');?>
 </div>
  <div><?php echo html::submitButton()?></div>
</div>

<table class='cont-rt5'>
  <tr valign='top'>
    <td>
      <table class='table-1 bd-none'>
        <tr class='bd-none'><td class='bd-none'>
          <fieldset>
            <legend><?php echo $lang->bug->legendSteps;?></legend>
            <div class='w-p90'><?php echo html::textarea('steps', htmlspecialchars($bug->steps), "rows='12'");?></div>
          </fieldset>
          <fieldset>
          <legend><?php echo $lang->bug->legendComment;?></legend>
            <?php echo html::textarea('comment', '', "rows='6' class='area-1'");?>
          </fieldset>
          <fieldset>
          <legend><?php echo $lang->bug->legendAttatch;?></legend>
          <?php echo $this->fetch('file', 'buildform', 'filecount=2');?>
          </fieldset>
          <div class='a-center'>
            <?php 
            echo html::submitButton();
            $browseLink = $app->session->bugList != false ? $app->session->bugList : inlink('browse', "productID=$bug->product");
            echo html::linkButton($lang->goback, $browseLink);
            ?>
          </div>
        </td></tr>
      </table>
      <?php include '../../common/view/action.html.php';?>
    </td>
    <td class='divider'></td>
    <td class='side'>
      <fieldset>
        <legend><?php echo $lang->bug->legendBasicInfo;?></legend>
        <table class='table-1 a-left' cellpadding='0' cellspacing='0'>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->product;?></td>
            <td>
              <?php echo html::select('product', $products, $productID, "onchange=loadAll(this.value);");?>
            </td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->module;?></td>
            <td>
              <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $currentModuleID);?></span>
            </td>
          </tr>

          <tr>
            <td class='rowhead'><?php echo $lang->bug->type;?></td>
            <td><?php echo html::select('type', $lang->bug->typeList, $bug->type, 'class=select-3');?>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->severity;?></td>
            <td><?php echo html::select('severity', $lang->bug->severityList, $bug->severity, 'class=select-3');?>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->pri;?></td>
            <td><?php echo html::select('pri', $lang->bug->priList, $bug->pri, 'class=select-3');?>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->status;?></td>
            <td><?php echo html::select('status', $lang->bug->statusList, $bug->status, 'class=select-3');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->confirmed;?></td>
            <td><?php echo $lang->bug->confirmedList[$bug->confirmed];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->assignedTo;?></td>
            <td><?php echo html::select('assignedTo', $users, $bug->assignedTo, 'class=select-3');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->os;?></td>
            <td><?php echo html::select('os', $lang->bug->osList, $bug->os, 'class=select-3');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->browser;?></td>
            <td><?php echo html::select('browser', $lang->bug->browserList, $bug->browser, 'class=select-3');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->keywords;?></td>
            <td><?php echo html::input('keywords', $bug->keywords, 'class="text-3"');?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendPrjStoryTask;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->bug->project;?></td>
            <td><span id='projectIdBox'><?php echo html::select('project', $projects, $bug->project, 'class=select-3 onchange=loadProjectRelated(this.value)');?></span></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->story;?></td>
            <td><div id='storyIdBox'><?php echo html::select('story', $stories, $bug->story, "class=select-3");?></div>
            </td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->task;?></td>
            <td><div id='taskIdBox'><?php echo html::select('task', $tasks, $bug->task, 'class=select-3');?></div></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendLife;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->bug->openedBy;?></td>
            <td><?php echo $users[$bug->openedBy];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->openedBuild;?></td>
            <td><span id='openedBuildBox'><?php echo html::select('openedBuild[]', $openedBuilds, $bug->openedBuild, 'size=4 multiple=multiple class=select-3');?></span></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolvedBy;?></td>
            <td><?php echo html::select('resolvedBy', $users, $bug->resolvedBy, 'class=select-3');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolvedDate;?></td>
            <td><?php echo html::input('resolvedDate', $bug->resolvedDate, 'class=text-3');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolvedBuild;?></td>
            <td><span id='resolvedBuildBox'><?php echo html::select('resolvedBuild', $resolvedBuilds, $bug->resolvedBuild, 'class=select-3');?></span></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolution;?></td>
            <td><?php echo html::select('resolution', $lang->bug->resolutionList, $bug->resolution, 'class=select-3 onchange=setDuplicate(this.value)');?></td>
          </tr>
          <tr id='duplicateBugBox' <?php if($bug->resolution != 'duplicate') echo "style='display:none'";?>>
            <td class='rowhead'><?php echo $lang->bug->duplicateBug;?></td>
            <td><?php echo html::input('duplicateBug', $bug->duplicateBug, 'class=text-3');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->closedBy;?></td>
            <td><?php echo html::select('closedBy', $users, $bug->closedBy, 'class=select-3');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->closedDate;?></td>
            <td><?php echo html::input('closedDate', $bug->closedDate, 'class=text-3');?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->bug->legendMisc;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->bug->mailto;?></td>
            <td><?php echo html::input('mailto', $bug->mailto, 'class="text-3"');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->linkBug;?></td>
            <td><?php echo html::input('linkBug', $bug->linkBug, 'class="text-3"');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->case;?></td>
            <td><?php echo html::input('case', $bug->case, 'class="text-3"');?></td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
