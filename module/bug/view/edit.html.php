<?php
/**
 * The edit file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: edit.html.php 4259 2013-01-24 05:49:40Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/chosen.html.php';
include '../../common/view/chosen.html.php';
include '../../common/view/alert.html.php';
include '../../common/view/kindeditor.html.php';
js::set('page'                   , 'edit');
js::set('changeProductConfirmed' , false);
js::set('changeProjectConfirmed' , false);
js::set('confirmChangeProduct'   , $lang->bug->confirmChangeProduct);
js::set('planID'                 , $bug->plan);
js::set('oldProjectID'           , $bug->project);
js::set('oldStoryID'             , $bug->story);
js::set('oldTaskID'              , $bug->task);
js::set('oldOpenedBuild'         , $bug->openedBuild);
js::set('oldResolvedBuild'       , $bug->resolvedBuild);
?>

<form method='post' target='hiddenwin' enctype='multipart/form-data' id='dataform'>
<div id='titlebar'>
  <div id='main'>
  BUG #<?php echo $bug->id . $lang->colon;?>
  <?php echo html::input('title', str_replace("'","&#039;",$bug->title), 'class=form-control');?>
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
            <?php echo html::textarea('steps', htmlspecialchars($bug->steps), "rows='12' class='form-control'");?>
          </fieldset>
          <fieldset>
          <legend><?php echo $lang->bug->legendComment;?></legend>
            <?php echo html::textarea('comment', '', "rows='6' class='form-control'");?>
          </fieldset>
          <fieldset>
          <legend><?php echo $lang->bug->legendAttatch;?></legend>
          <?php echo $this->fetch('file', 'buildform', 'filecount=2');?>
          </fieldset>
          <div class='text-center'>
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
            <td><?php echo $lang->bug->product;?></td>
            <td><?php echo html::select('product', $products, $productID, "onchange=loadAll(this.value); class='form-control'");?></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->module;?></td>
            <td>
              <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $currentModuleID, "onchange='loadModuleRelated()' class='form-control'");?></span>
            </td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->productplan;?></td>
            <td>
              <span id="planIdBox"><?php echo html::select('plan', $plans, $bug->plan, "class='form-control'");?></span>
            </td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->type;?></td>
            <td><?php echo html::select('type', $lang->bug->typeList, $bug->type, "class='form-control'");?>
          </tr>
          <tr>
            <td><?php echo $lang->bug->severity;?></td>
            <td><?php echo html::select('severity', $lang->bug->severityList, $bug->severity, "class='form-control'");?>
          </tr>
          <tr>
            <td><?php echo $lang->bug->pri;?></td>
            <td><?php echo html::select('pri', $lang->bug->priList, $bug->pri, "class='form-control'");?>
          </tr>
          <tr>
            <td><?php echo $lang->bug->status;?></td>
            <td><?php echo html::select('status', $lang->bug->statusList, $bug->status, "class='form-control'");?></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->confirmed;?></td>
            <td><?php echo $lang->bug->confirmedList[$bug->confirmed];?></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->assignedTo;?></td>
            <td><?php echo html::select('assignedTo', $users, $bug->assignedTo, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->os;?></td>
            <td><?php echo html::select('os', $lang->bug->osList, $bug->os, "class='form-control'");?></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->browser;?></td>
            <td><?php echo html::select('browser', $lang->bug->browserList, $bug->browser, "class='form-control'");?></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->keywords;?></td>
            <td><?php echo html::input('keywords', $bug->keywords, 'class="form-control"');?></td>
          </tr>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->bug->mailto;?></td>
            <td><?php echo html::select('mailto[]', $users, str_replace(' ', '', $bug->mailto), 'class="form-control" multiple');?></td>
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
            <td><?php echo $lang->bug->story;?></td>
            <td><div id='storyIdBox'><?php echo html::select('story', $stories, $bug->story, "class=select-3");?></div>
            </td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->task;?></td>
            <td><div id='taskIdBox'><?php echo html::select('task', $tasks, $bug->task, "class='form-control'");?></div></td>
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
            <td><?php echo $lang->bug->openedBuild;?></td>
            <td><span id='openedBuildBox'><?php echo html::select('openedBuild[]', $openedBuilds, $bug->openedBuild, 'size=4 multiple=multiple class=select-3');?></span></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->resolvedBy;?></td>
            <td><?php echo html::select('resolvedBy', $users, $bug->resolvedBy, "class='form-control'");?></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->resolvedDate;?></td>
            <td><?php echo html::input('resolvedDate', $bug->resolvedDate, 'class=form-control');?></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->resolvedBuild;?></td>
            <td><span id='resolvedBuildBox'><?php echo html::select('resolvedBuild', $resolvedBuilds, $bug->resolvedBuild, "class='form-control'");?></span></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->resolution;?></td>
            <td><?php echo html::select('resolution', $lang->bug->resolutionList, $bug->resolution, 'class=select-3 onchange=setDuplicate(this.value)');?></td>
          </tr>
          <tr id='duplicateBugBox' <?php if($bug->resolution != 'duplicate') echo "style='display:none'";?>>
            <td><?php echo $lang->bug->duplicateBug;?></td>
            <td><?php echo html::input('duplicateBug', $bug->duplicateBug, 'class=form-control');?></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->closedBy;?></td>
            <td><?php echo html::select('closedBy', $users, $bug->closedBy, "class='form-control'");?></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->closedDate;?></td>
            <td><?php echo html::input('closedDate', $bug->closedDate, 'class=form-control');?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->bug->legendMisc;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td><?php echo $lang->bug->linkBug;?></td>
            <td><?php echo html::input('linkBug', $bug->linkBug, 'class="form-control"');?></td>
          </tr>
          <tr>
            <td><?php echo $lang->bug->case;?></td>
            <td><?php echo html::input('case', $bug->case, 'class="form-control"');?></td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
