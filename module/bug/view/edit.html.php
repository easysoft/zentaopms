<?php
/**
 * The edit file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: edit.html.php 4259 2013-01-24 05:49:40Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/datepicker.html.php';
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

<form class='form-condensed' method='post' target='hiddenwin' enctype='multipart/form-data' id='dataform'>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['bug']);?> <strong><?php echo $bug->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, '', 
    'class="bug-title"');?></strong>
    <small><?php echo html::icon($lang->icons['edit']) . ' ' . $lang->bug->edit;?></small>
  </div>
  <div class='actions'>
    <?php echo html::submitButton($lang->save)?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <div class='form-group'>
        <div class='input-group'>
          <input type='hidden' id='color' name='color' data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='<?php echo $lang->bug->colorTag ?>' value='<?php echo $bug->color ?>' data-update-text='#title, .bug-title'>
          <?php echo html::input('title', str_replace("'","&#039;",$bug->title), 'class="form-control" placeholder="' . $lang->bug->title . '"');?>
        </div>
      </div>
      <fieldset>
        <legend><?php echo $lang->bug->legendSteps;?></legend>
        <div class='form-group'><?php echo html::textarea('steps', htmlspecialchars($bug->steps), "rows='12' class='form-control'");?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->bug->legendComment;?></legend>
        <div class='form-group'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->bug->legendAttatch;?></legend>
        <div class='form-group'><?php echo $this->fetch('file', 'buildform');?></div>
      </fieldset>
      <div class='actions'>
        <?php 
        echo html::hidden('lastEditedDate', $bug->lastEditedDate);
        echo html::submitButton();
        $browseLink = $app->session->bugList != false ? $app->session->bugList : inlink('browse', "productID=$bug->product");
        echo html::linkButton($lang->goback, $browseLink);
        ?>
      </div>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->bug->legendBasicInfo;?></legend>
        <table class='table table-form'>
          <tr>
            <th class='w-80px'><?php echo $lang->bug->product;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('product', $products, $productID, "onchange='loadAll(this.value)' class='form-control chosen'");?>
                <?php if($this->session->currentProductType != 'normal') echo html::select('branch', $branches, $bug->branch, "onchange='loadBranch();' class='form-control' style='width:65px'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->module;?></th>
            <td>
              <div class='input-group' id='moduleIdBox'>
              <?php 
              echo html::select('module', $moduleOptionMenu, $currentModuleID, "onchange='loadModuleRelated()' class='form-control chosen'");
              if(count($moduleOptionMenu) == 1)
              {
                  echo "<span class='input-group-addon'>";
                  echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=bug&currentModuleID=0&branch=$bug->branch"), $lang->tree->manage, '_blank');
                  echo '&nbsp; ';
                  echo html::a("javascript:loadProductModules($productID)", $lang->refresh);
                  echo '</span>';
              }
              ?>
              </div>
            </td>
          </tr>
          <?php if($this->config->global->flow != 'onlyTest'):?>
          <tr>
            <th><?php echo $lang->bug->productplan;?></th>
            <td>
              <span id="planIdBox"><?php echo html::select('plan', $plans, $bug->plan, "class='form-control chosen'");?></span>
            </td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->bug->type;?></th>
            <td>
              <?php
              /**
               * Remove designchange, newfeature, trackings from the typeList, because should be tracked in story or task. 
               * These thress types if upgrade from bugfree2.x.
               */
              if($bug->type != 'designchange') unset($lang->bug->typeList['designchange']);
              if($bug->type != 'newfeature')   unset($lang->bug->typeList['newfeature']);
              if($bug->type != 'trackthings')  unset($lang->bug->typeList['trackthings']);
              echo html::select('type', $lang->bug->typeList, $bug->type, "class='form-control'");
              ?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->severity;?></th>
            <td><?php echo html::select('severity', $lang->bug->severityList, $bug->severity, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->pri;?></th>
            <td><?php echo html::select('pri', $lang->bug->priList, $bug->pri, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->status;?></th>
            <td><?php echo html::select('status', $lang->bug->statusList, $bug->status, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->confirmed;?></th>
            <td><?php echo $lang->bug->confirmedList[$bug->confirmed];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->assignedTo;?></th>
            <td><?php echo html::select('assignedTo', $users, $bug->assignedTo, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->deadline;?></th>
            <td><?php echo html::input('deadline', $bug->deadline, "class='form-control form-date'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->os;?></th>
            <td><?php echo html::select('os', $lang->bug->osList, $bug->os, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->browser;?></th>
            <td><?php echo html::select('browser', $lang->bug->browserList, $bug->browser, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->keywords;?></th>
            <td><?php echo html::input('keywords', $bug->keywords, 'class="form-control"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->mailto;?></th>
            <td><?php echo html::select('mailto[]', $users, str_replace(' ', '', $bug->mailto), 'class="form-control chosen" multiple');?></td>
          </tr>
        </table>
      </fieldset>

      <?php if($this->config->global->flow != 'onlyTest'):?>
      <fieldset>
        <legend><?php echo $lang->bug->legendPrjStoryTask;?></legend>
        <table class='table table-form'>
          <tr>
            <th class='w-80px'><?php echo $lang->bug->project;?></th>
            <td><span id='projectIdBox'><?php echo html::select('project', $projects, $bug->project, "class='form-control chosen' onchange='loadProjectRelated(this.value)'");?></span></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->story;?></th>
            <td><div id='storyIdBox'><?php echo html::select('story', $stories, $bug->story, "class='form-control chosen'");?></div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->task;?></th>
            <td><div id='taskIdBox'><?php echo html::select('task', $tasks, $bug->task, "class='form-control chosen'");?></div></td>
          </tr>
        </table>
      </fieldset>
      <?php endif;?>

      <fieldset>
        <legend><?php echo $lang->bug->legendLife;?></legend>
        <table class='table table-form'>
          <tr>
            <th class='w-80px'><?php echo $lang->bug->openedBy;?></th>
            <td><?php echo $users[$bug->openedBy];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->openedBuild;?></th>
            <td>
              <div class='input-group'>
                <span id='openedBuildBox'><?php echo html::select('openedBuild[]', $openedBuilds, $bug->openedBuild, 'size=4 multiple=multiple class="chosen form-control"');?></span>
                <span class='input-group-btn'><?php echo html::commonButton($lang->bug->allBuilds, "class='btn btn-default' onclick='loadAllBuilds(this)'")?></span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->resolvedBy;?></th>
            <td><?php echo html::select('resolvedBy', $users, $bug->resolvedBy, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->resolvedDate;?></th>
            <td><?php echo html::input('resolvedDate', $bug->resolvedDate, 'class=form-control');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->resolvedBuild;?></th>
            <td>
              <div class='input-group'>
                <span id='resolvedBuildBox'><?php echo html::select('resolvedBuild', $resolvedBuilds, $bug->resolvedBuild, "class='form-control chosen'");?></span>
                <span class='input-group-btn'><?php echo html::commonButton($lang->bug->allBuilds, "class='btn btn-default' onclick='loadAllBuilds(this)'")?></span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->resolution;?></th>
            <td><?php echo html::select('resolution', $lang->bug->resolutionList, $bug->resolution, 'class=form-control onchange=setDuplicate(this.value)');?></td>
          </tr>
          <tr id='duplicateBugBox' <?php if($bug->resolution != 'duplicate') echo "style='display:none'";?>>
            <th><?php echo $lang->bug->duplicateBug;?></th>
            <td><?php echo html::input('duplicateBug', $bug->duplicateBug, 'class=form-control');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->closedBy;?></th>
            <td><?php echo html::select('closedBy', $users, $bug->closedBy, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->closedDate;?></th>
            <td><?php echo html::input('closedDate', $bug->closedDate, 'class=form-control');?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->bug->legendMisc;?></legend>
        <table class='table table-form'>
          <tr class='text-top'>
            <th class='w-80px'><?php echo $lang->bug->linkBug;?></th>
            <td>
              <?php echo html::a($this->createLink('bug', 'linkBugs', "bugID=$bug->id", '', true), $lang->bug->linkBugs, '', "data-toggle='modal' data-type='iframe' data-width='95%'");?>
              <ul class='list-unstyled' id='linkBugBox'>
              <?php
              if(isset($bug->linkBugTitles))
              {
                  foreach($bug->linkBugTitles as $linkBugID => $linkBugTitle)
                  {
                      echo '<li>';
                      echo html::a(inlink('view', "bugID=$linkBugID"), "#$linkBugID " . $linkBugTitle, '_blank');
                      echo html::a("javascript:unlinkBug($bug->id, $linkBugID)", '<i class="icon-remove"></i>', '', "title='{$lang->unlink}' style='float:right'");
                      echo '</li>';
                  }
              }
              ?>
              </ul>
            </td>
          </tr>
          <?php if($bug->case):?>
          <tr>
            <th><?php echo $lang->bug->fromCase;?></th>
            <td><?php echo html::input('case', $bug->case, 'class="form-control"');?></td>
          </tr>
          <?php endif;?>
        </table>
      </fieldset>
    </div>
  </div>
</div>
</form>
<?php include '../../common/view/footer.html.php';?>
