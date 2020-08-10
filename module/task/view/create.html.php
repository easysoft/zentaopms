<?php
/**
 * The create view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: create.html.php 5090 2013-07-10 05:49:24Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('toTaskList', $config->global->flow == 'onlyTask' || !empty($task->id));?>
<style>.title-group .has-icon-right{min-width:700px}</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->task->create;?></h2>
      <div class='btn-toolbar pull-right'>
        <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=task&section=custom&key=createFields')?>
        <?php include '../../common/view/customfield.html.php';?>
      </div>
    </div>
    <form class='main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->task->project;?></th>
          <td><?php echo html::select('project', $projects, $project->id, "class='form-control chosen' onchange='loadAll(this.value)' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->task->type;?></th>
          <td><?php echo html::select('type', $lang->task->typeList, $task->type, "class='form-control chosen' onchange='setOwners(this.value)' required");?></td>
          <td>
            <div class="checkbox-primary hidden" id='selectTestStoryBox'>
              <input type="checkbox" name='selectTestStory' id="selectTestStory" value='1' onchange='toggleSelectTestStory()' /><label for="selectTestStory" class="no-margin"><?php echo $lang->task->selectTestStory;?></label>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->task->module;?></th>
          <td id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $task->module, "class='form-control chosen' onchange='setStories(this.value, $project->id)'");?></td>
          <td>
            <div class="checkbox-primary">
              <input type="checkbox" id="showAllModule" <?php if($showAllModule) echo 'checked';?>><label for="showAllModule" class="no-margin"><?php echo $lang->task->allModule;?></label>
            </div>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->task->assignedTo;?></th>
          <td>
            <div class="input-group" id="dataPlanGroup">
              <?php echo html::select('assignedTo[]', $members, $task->assignedTo, "class='form-control chosen'");?>
              <?php echo html::input('teamMember', '', "class='form-control team-group fix-border hidden' readonly='readonly'");?>
              <span class="input-group-btn team-group hidden"><a class="btn br-0" href="#modalTeam" data-toggle="modal"><?php echo $lang->task->team;?></a></span>
            </div>
          </td>
          <td>
            <div class="checkbox-primary affair">
              <input type="checkbox" name="multiple" value="1" id="multipleBox"><label for="multipleBox" class="no-margin"><?php echo $lang->task->multiple;?></label>
            </div>
            <button id='selectAllUser' type="button" class="btn btn-link<?php if($task->type !== 'affair') echo ' hidden';?>"><?php echo $lang->task->selectAllUser;?></button>
          </td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->task->status;?></th>
          <td><?php echo html::hidden('status', 'wait');?></td>
        </tr>
        <?php $this->printExtendFields('', 'table');?>
        <?php if(strpos(",$showFields,", ',story,') !== false and $config->global->flow != 'onlyTask' and $project->type != 'ops'):?>
        <tr>
          <th><?php echo $lang->task->story;?></th>
          <td colspan='3'>
            <?php if(empty($stories)):?>
            <span id='storyBox'><?php printf($lang->task->noticeLinkStory, html::a($this->createLink('project', 'linkStory', "projectID=$project->id"), $lang->project->linkStory, '_blank', 'class="text-primary"'), html::a("javascript:loadStories($project->id)", $lang->refresh, '', 'class="text-primary"'));?></span>
            <?php else:?>
            <div class='input-group'>
              <?php echo html::select('story', array($task->story => $stories[$task->story]), $task->story, "class='form-control chosen' onchange='setStoryRelated();'");?>
              <span class='input-group-btn' id='preview'><a href='#' class='btn iframe'><?php echo $lang->preview;?></a></span>
            </div>
            <?php endif;?>
          </td>
        </tr>
        <?php endif;?>
        <?php if($stories and $config->global->flow != 'onlyTask' and $project->type != 'ops'):?>
        <tr id='testStoryBox' class='hidden'>
          <th><?php echo $lang->task->selectTestStory;?></th>
          <td colspan='3'>
            <table class='table table-form mg-0 table-bordered'>
              <thead>
                <tr>
                  <th><?php echo $lang->task->storyAB;?></th>
                  <th class='w-100px'><?php echo $lang->task->pri;?></th>
                  <th class='w-300px'><?php echo $lang->task->datePlan;?></th>
                  <th class='w-150px'><?php echo $lang->task->assignedTo;?></th>
                  <th class='w-80px'><?php echo $lang->task->estimate;?></th>
                  <th class='w-80px'><?php echo $lang->actions;?></th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 0;?>
                <?php foreach($stories as $storyID => $storyTitle):?>
                <?php if(empty($storyID) or isset($testStoryIdList[$storyID])) continue;?>
                <tr>
                  <td><?php echo html::select("testStory[]", array($storyID => $storyTitle), $storyID, "class='form-control chosen'");?></td>
                  <td><?php echo html::select("testPri[]", $lang->task->priList, $task->pri, "class='form-control chosen'");?></td>
                  <td>
                    <div class='input-group'>
                      <?php echo html::input("testEstStarted[]", $task->estStarted, "class='form-control form-date' placeholder='{$lang->task->estStarted}'");?>
                      <span class='input-group-addon fix-border'>~</span>
                      <?php echo html::input("testDeadline[]", $task->deadline, "class='form-control form-date' placeholder='{$lang->task->deadline}'");?>
                    </div>
                  </td>
                  <td><?php echo html::select("testAssignedTo[]", $members, $task->assignedTo, "class='form-control chosen'");?></td>
                  <td><?php echo html::input("testEstimate[]", '', "class='form-control'");?></td>
                  <td class='text-center'>
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm" tabindex="-1" onclick='addItem(this)'><i class="icon icon-plus"></i></button>
                      <button type="button" class="btn btn-sm" tabindex="-1" onclick='removeItem(this)'><i class="icon icon-close"></i></button>
                    </div>
                  </td>
                </tr>
                <?php $i++;?>
                <?php if($i > 30) break;?>
                <?php endforeach;?>
              </tbody>
            </table>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->task->name;?></th>
          <td colspan='3'>
            <div class="input-group title-group">
              <div class="input-control has-icon-right">
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->task->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#name"  data-provide="colorpicker">
                </div>
                <?php echo html::input('name', $task->name, "class='form-control' required");?>
                <?php if($config->global->flow != 'onlyTask'):?>
                <a href='javascript:copyStoryTitle();' id='copyButton' class='input-control-icon-right'><?php echo $lang->task->copyStoryTitle;?></a>
                <?php echo html::hidden("storyEstimate") . html::hidden("storyDesc") . html::hidden("storyPri");?>
                <?php endif;?>
              </div>
              <?php if(strpos(",$showFields,", ',pri,') !== false): // begin print pri selector?>
              <span class="input-group-addon fix-border br-0"><?php echo $lang->task->pri;?></span>
              <?php
              $hasCustomPri = false;
              foreach($lang->task->priList as $priKey => $priValue)
              {
                  if(!empty($priKey) and (string)$priKey != (string)$priValue)
                  {
                      $hasCustomPri = true;
                      break;
                  }
              }
              $priList = $lang->task->priList;
              if(end($priList)) unset($priList[0]);
              if(!isset($priList[$task->pri]))
              {
                  reset($priList);
                  $task->pri = key($priList);
              }
              ?>
              <?php if($hasCustomPri):?>
              <?php echo html::select('pri', (array)$priList, $task->pri, "class='form-control'");?>
              <?php else: ?>
              <div class="input-group-btn pri-selector" data-type="pri">
                <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                  <span class="pri-text"><span class="label-pri label-pri-<?php echo empty($task->pri) ? '0' : $task->pri?>" title="<?php echo $task->pri?>"><?php echo $task->pri?></span></span> &nbsp;<span class="caret"></span>
                </button>
                <div class='dropdown-menu pull-right'>
                  <?php echo html::select('pri', (array)$priList, $task->pri, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
                </div>
              </div>
              <?php endif; ?>
              <?php endif; // end print pri selector ?>
              <?php if(strpos(",$showFields,", ',estimate,') !== false):?>
              <div class='table-col w-120px'>
                <div class="input-group">
                  <span class="input-group-addon fix-border br-0"><?php echo $lang->task->estimateAB;?></span>
                  <input type="text" name="estimate" id="estimate" value="<?php echo $task->estimate;?>" class="form-control" autocomplete="off">
                </div>
              </div>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->task->desc;?></th>
          <td colspan='3'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=task&link=desc');?>
            <?php echo html::textarea('desc', $task->desc, "rows='10' class='form-control'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->files;?></th>
          <td colspan='3'><?php echo $this->fetch('file', 'buildform');?></td>
        </tr>
        <?php
        $hiddenEstStarted = strpos(",$showFields,", ',estStarted,') === false;
        $hiddenDeadline   = strpos(",$showFields,", ',deadline,')   === false;
        ?>
        <?php if(!$hiddenEstStarted or !$hiddenDeadline):?>
        <tr>
          <th><?php echo $lang->task->datePlan;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php if(!$hiddenEstStarted):?>
              <?php echo html::input('estStarted', $task->estStarted, "class='form-control form-date' placeholder='{$lang->task->estStarted}'");?>
              <?php endif;?>
              <?php if(!$hiddenEstStarted and !$hiddenDeadline):?>
              <span class='input-group-addon fix-border'>~</span>
              <?php endif;?>
              <?php if(!$hiddenDeadline):?>
              <?php echo html::input('deadline', $task->deadline, "class='form-control form-date' placeholder='{$lang->task->deadline}'");?>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <?php endif;?>
        <?php if(strpos(",$showFields,", ',mailto,') !== false):?>
        <tr>
          <th><?php echo $lang->story->mailto;?></th>
          <td colspan='3'>
            <div class="input-group">
              <?php echo html::select('mailto[]', $project->acl == 'private' ? $members : $users, str_replace(' ', '', $task->mailto), "class='form-control chosen' data-placeholder='{$lang->chooseUsersToMail}' multiple");?>
              <?php echo $this->fetch('my', 'buildContactLists');?>
            </div>
          </td>
        </tr>
        <?php endif;?>
        <tr <?php echo $config->global->flow == 'onlyTask' ? "class='hidden'" : '';?> id='after-tr'>
          <th><?php echo $lang->task->afterSubmit;?></th>
          <td colspan='3'><?php echo html::radio('after', $lang->task->afterChoices, $config->global->flow == 'onlyTask' || !empty($task->id) ? 'toTaskList' : 'continueAdding');?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>

      <div class='modal fade modal-team' id='modalTeam' data-scroll-inside='false'>
        <div class='modal-dialog'>
          <div class='modal-content with-padding'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'>
                <i class="icon icon-close"></i>
              </button>
              <h4 class='modal-title'><?php echo $lang->task->team;?></h4>
            </div>
            <div class='modal-body'>
              <table class="table table-form" id='taskTeamEditor'>
                <tbody class='sortable'>
                  <tr class='template'>
                    <td><?php echo html::select("team[]", $members, '', "class='form-control chosen'");?></td>
                    <td>
                      <div class='input-group'>
                        <?php echo html::input("teamEstimate[]", '', "class='form-control text-center' placeholder='{$lang->task->estimateAB}'") ?>
                        <span class='input-group-addon'><?php echo $lang->task->hour;?></span>
                      </div>
                    </td>
                    <td class='w-130px sort-handler'>
                      <button type="button" class="btn btn-link btn-sm btn-icon btn-add"><i class="icon icon-plus"></i></button>
                      <button type='button' class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
                      <button type="button" class="btn btn-link btn-sm btn-icon btn-delete"><i class="icon icon-close"></i></button>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan='3' class='text-center'><?php echo html::a('javascript:void(0)', $lang->confirm, '', "class='btn btn-primary' data-dismiss='modal'");?></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php js::set('stories', $stories);?>
<?php js::set('storyPinYin', (empty($config->isINT) and class_exists('common')) ? common::convert2Pinyin($stories) : array());?>
<?php js::set('testStoryIdList', $testStoryIdList);?>
<?php js::set('projectID', $project->id);?>
<?php include '../../common/view/footer.html.php';?>
