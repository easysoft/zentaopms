<?php
/**
 * The create view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: create.html.php 5090 2013-07-10 05:49:24Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('toTaskList', !empty($task->id));?>
<?php js::set('blockID', $blockID);?>
<?php js::set('executionID', $execution->id);?>
<?php js::set('ditto', $lang->task->ditto);?>
<?php js::set('teamMemberError', $lang->task->error->teamMember);?>
<?php js::set('vision', $config->vision);?>
<?php js::set('requiredFields', $config->task->create->requiredFields);?>
<?php js::set('estimateNotEmpty', sprintf($lang->error->gt, $lang->task->estimate, '0'))?>
<?php js::set('lifetime', $execution->lifetime);?>
<?php js::set('attribute', $execution->attribute);?>
<?php js::set('lifetimeList', $lifetimeList);?>
<?php js::set('attributeList', $attributeList);?>
<?php js::set('hasProduct', $execution->hasProduct);?>
<?php
$requiredFields = array();
foreach(explode(',', $config->task->create->requiredFields) as $field)
{
    if($field) $requiredFields[$field] = '';
    if($field and strpos($showFields, $field) === false) $showFields .= ',' . $field;
}
?>
<?php js::set('showFields', $showFields);?>
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
        <?php if($execution->type != 'kanban' or $this->config->vision == 'lite'):?>
        <tr class="<?php echo !$execution->multiple ? 'hidden' : '';?>">
          <th><?php echo $lang->task->execution;?></th>
          <td><?php echo html::select('execution', $executions, $execution->id, "class='form-control chosen' onchange='loadAll(this.value)' required");?></td><td></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->task->type;?></th>
          <td><?php echo html::select('type', $lang->task->typeList, $task->type, "class='form-control chosen' onchange='setOwners(this.value)' required");?></td>
          <td>
            <div class="checkbox-primary c-selectStory hidden" id='selectTestStoryBox'>
              <input type="checkbox" name='selectTestStory' id="selectTestStory" value='1' onchange='toggleSelectTestStory()' /><label for="selectTestStory" class="no-margin"><?php echo $lang->task->selectTestStory;?></label>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->task->module;?></th>
          <td id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $task->module, "class='form-control chosen' onchange='setStories(this.value, $execution->id)'");?></td>
          <td>
            <div class="checkbox-primary c-modulel">
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
              <?php
              $teamMember = '';
              if(!empty($task->team))
              {
                  foreach($task->team as $team) $teamMember .= ' ' . zget($members, $team->account);
              }
              ?>
              <?php echo html::input('teamMember', $teamMember, "class='form-control team-group fix-border hidden' readonly='readonly'");?>
              <span class="input-group-btn team-group hidden"><a class="btn br-0" href="#modalTeam" data-toggle="modal"><?php echo $lang->task->team;?></a></span>
            </div>
          </td>
          <td colspan='2'>
            <div class="checkbox-primary c-multipleTask affair" style='display: inline-block; margin-right: 10px'>
              <input type="checkbox" name="multiple" value="1" id="multipleBox" /><label for="multipleBox" class="no-margin"><?php echo $lang->task->multiple;?></label>
            </div>
            <div class='hidden modeBox' style='display: inline-block'><?php echo html::radio('mode', $lang->task->modeList, !empty($task->mode) ? $task->mode: 'linear');?></div>
          <button id='selectAllUser' type="button" class="btn btn-link<?php if($task->type !== 'affair') echo ' hidden';?>"><?php echo $lang->task->selectAllUser;?></button>
          </td>
        </tr>
        <?php if($execution->type == 'kanban'):?>
        <tr>
          <th><?php echo $lang->kanbancard->region;?></th>
          <td><?php echo html::select('region', $regionPairs, $regionID, "onchange='setLane(this.value)' class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbancard->lane;?></th>
          <td><?php echo html::select('lane', $lanePairs, $laneID, "class='form-control chosen'");?></td>
        </tr>
        <?php endif;?>
        <tr class='hide'>
          <th><?php echo $lang->task->status;?></th>
          <td><?php echo html::hidden('status', 'wait');?></td>
        </tr>
        <?php $this->printExtendFields('', 'table', 'columns=3');?>
        <?php $hiddenStory = (strpos(",$showFields,", ',story,') !== false and $features['story']) ? '' : 'hidden'?>
        <tr class="<?php echo $hiddenStory?> storyBox">
          <th><?php echo $lang->task->story;?></th>
          <td colspan='3'>
            <span id='storyBox' class="<?php if(!empty($stories)) echo 'hidden';?> ">
              <?php
              $noticeLinkStory = sprintf($lang->task->noticeLinkStory, html::a($this->createLink('execution', 'linkStory', "executionID=$execution->id"), $lang->execution->linkStory, '', 'class="text-primary"'), html::a("javascript:loadStories($execution->id)", $lang->refresh, '', 'class="text-primary"'));
              if(empty($execution->hasProduct)) $noticeLinkStory = $lang->task->noticeLinkStoryNoProduct;
              echo $noticeLinkStory;
              ?>
            </span>
            <div class='input-group <?php if(empty($stories)) echo "hidden";?>'>
              <?php echo html::select('story', $stories, $task->story, "class='form-control chosen' onchange='setStoryRelated();'");?>
              <?php if(common::hasPriv('execution', 'storyView')):?>
              <span class='input-group-btn' id='preview'><a href='#' class='btn iframe' data-width="85%" data-height="300px"><?php echo $lang->preview;?></a></span>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <?php if($execution->lifetime != 'ops' and !in_array($execution->attribute, array('request', 'review'))):?>
        <tr id='testStoryBox' class='hidden'>
          <th><?php echo $lang->task->selectTestStory;?></th>
          <td colspan='3'>
            <table class='table table-form mg-0 table-bordered'>
              <thead>
                <tr class='text-center'>
                  <th class='c-name'><?php echo $lang->task->storyAB;?></th>
                  <th class='c-pri <?php if(isset($requiredFields['pri'])) echo 'required';?>'><?php echo $lang->task->pri;?></th>
                  <th class='c-date <?php if(isset($requiredFields['estStarted'])) echo 'required';?>'><?php echo $lang->task->estStarted;?></th>
                  <th class='c-date <?php if(isset($requiredFields['deadline'])) echo 'required';?>'><?php echo $lang->task->deadline;?></th>
                  <th class='c-assignedTo'><?php echo $lang->task->assignedTo;?></th>
                  <th class='c-estimate <?php if(isset($requiredFields['estimate'])) echo 'required';?>'><?php echo $lang->task->estimate;?></th>
                  <th class='c-actions'><?php echo $lang->actions;?></th>
                </tr>
              </thead>
              <tbody class="resarch">
                <?php $i = 0;?>
                <?php foreach($testStories as $storyID => $storyTitle):?>
                <?php if($i > 0) $members['ditto'] = $lang->task->ditto;?>
                <tr>
                  <td><?php echo html::select("testStory[$i]", array($storyID => $storyTitle), $storyID, "class='form-control chosen'");?></td>
                  <td><?php echo html::select("testPri[$i]", $lang->task->priList, $task->pri, "class='form-control chosen'");?></td>
                  <td>
                    <div class='input-group'>
                      <?php
                      echo html::input("testEstStarted[$i]", $task->estStarted, "class='startInput form-control form-date' onchange='hiddenDitto(this)' placeholder='{$lang->task->estStarted}'");
                      if($i != 0) echo "<span class='input-group-addon estStartedBox'><input type='checkbox' name='estStartedDitto[$i]' id='estStartedDitto' " . ($i > 0 ? "checked" : '') . " /> {$lang->task->ditto}</span>";
                      ?>
                    </div>
                  <td>
                    <div class='input-group'>
                      <?php
                      echo html::input("testDeadline[$i]", $task->deadline, "class='deadlineInput form-control form-date' onchange='hiddenDitto(this)' placeholder='{$lang->task->deadline}'");
                      if($i != 0) echo "<span class='input-group-addon deadlineBox'><input type='checkbox' name='deadlineDitto[$i]' id='deadlineDitto' " . ($i > 0 ? "checked" : '') . " /> {$lang->task->ditto}</span>";
                      ?>
                    </div>
                  </td>
                  <td><?php echo html::select("testAssignedTo[$i]", $members, $i == 0 ? $task->assignedTo : 'ditto', "class='form-control chosen'");?></td>
                  <td><?php echo html::input("testEstimate[$i]", '', "class='form-control'");?></td>
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
                <?php js::set('index', $i);?>
                <?php unset($members['ditto']);?>
              </tbody>
            </table>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->task->name;?></th>
          <td colspan='3'>
            <div class='keep-row-height'>
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
                  <a href='javascript:copyStoryTitle();' id='copyButton' class='input-control-icon-right'><?php echo $lang->task->copyStoryTitle;?></a>
                  <?php echo html::hidden("storyEstimate") . html::hidden("storyDesc") . html::hidden("storyPri");?>
                </div>
                <?php $hiddenPri = strpos(",$showFields,", ',pri,') !== false ? '' : 'hidden'; // begin print pri selector?>
                <span class="input-group-addon fix-border br-0 <?php echo $hiddenPri;?> priBox"><?php echo $lang->task->pri;?></span>
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
                <?php echo html::select('pri', (array)$priList, $task->pri, "class='form-control $hiddenPri'");?>
                <?php else: ?>
                <div class="input-group-btn pri-selector <?php echo $hiddenPri;?> priBox" data-type="pri">
                  <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                    <span class="pri-text"><span class="label-pri label-pri-<?php echo empty($task->pri) ? '0' : $task->pri?>" title="<?php echo $task->pri?>"><?php echo $task->pri?></span></span> &nbsp;<span class="caret"></span>
                  </button>
                  <div class='dropdown-menu pull-right'>
                    <?php echo html::select('pri', (array)$priList, $task->pri, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
                  </div>
                </div>
                <?php endif; ?>
                <?php $hiddenEstimate = strpos(",$showFields,", ',estimate,') !== false ? '' : 'hidden';?>
                <div class="table-col w-120px <?php echo $hiddenEstimate;?> estimateBox">
                  <div class="input-group">
                    <span class="input-group-addon fix-border br-0"><?php echo $lang->task->estimateAB;?></span>
                    <input type="text" name="estimate" id="estimate" value="<?php echo $task->estimate;?>" class="form-control" autocomplete="off">
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->task->desc;?></th>
          <td colspan='3'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=task&link=desc');?>
            <?php echo html::textarea('desc', htmlSpecialString($task->desc), "rows='10' class='form-control kindeditor'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->files;?></th>
          <td colspan='3'><?php echo $this->fetch('file', 'buildform');?></td>
        </tr>
        <?php
        $hiddenEstStarted = strpos(",$showFields,", ',estStarted,') === false ? 'hidden' : '';
        $hiddenDeadline   = strpos(",$showFields,", ',deadline,')   === false ? 'hidden' : '';
        $hiddenDatePlan   = (!$hiddenEstStarted or !$hiddenDeadline) ? '' : 'hidden';
        ?>
        <tr class="<?php echo $hiddenDatePlan?> datePlanBox">
          <th><?php echo $lang->task->datePlan;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::input('estStarted', $task->estStarted, "class='form-control form-date $hiddenEstStarted estStartedBox' placeholder='{$lang->task->estStarted}'");?>
              <?php $hiddenborder = (!$hiddenEstStarted and !$hiddenDeadline) ? '' : 'hidden';?>
              <span class="input-group-addon fix-border <?php echo $hiddenborder?> borderBox">~</span>
              <?php echo html::input('deadline', $task->deadline, "class='form-control form-date $hiddenDeadline deadlineBox' placeholder='{$lang->task->deadline}'");?>
            </div>
          </td>
        </tr>
        <?php $hiddenMailto = strpos(",$showFields,", ',mailto,') !== false ? '' : 'hidden';?>
        <tr class="<?php echo $hiddenMailto?> mailtoBox">
          <th><?php echo $lang->story->mailto;?></th>
          <td colspan='3'>
            <div class="input-group">
              <?php echo html::select('mailto[]', $users, str_replace(' ', '', $task->mailto), "class='form-control picker-select' data-placeholder='{$lang->chooseUsersToMail}' multiple");?>
              <?php if($execution->acl != 'private') echo $this->fetch('my', 'buildContactLists');?>
            </div>
          </td>
        </tr>
        <?php if(!isonlybody()):?>
        <tr id='after-tr'>
          <th><?php echo $lang->task->afterSubmit;?></th>
          <td colspan='3'><?php echo html::radio('after', $lang->task->afterChoices, !empty($task->id) ? 'toTaskList' : 'continueAdding');?></td>
        </tr>
        <?php endif;?>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo $gobackLink ? html::a($gobackLink, $lang->goback, '', 'class="btn btn-wide"') : html::backButton();?>
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
              <h4 class='modal-title'><?php echo $lang->task->teamSetting;?></h4>
            </div>
            <div class='modal-body'>
              <table class="table table-form" id='taskTeamEditor'>
                <tbody class='sortable'>
                  <?php include __DIR__ . DS . 'taskteam.html.php';?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan='4' class='text-center form-actions'><?php echo html::a('javascript:void(0)', $lang->confirm, '', "id='confirmButton' class='btn btn-primary'");?></td>
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
<table class='hidden' id='testStoryTemplate'>
  <tr>
    <td><?php echo html::select("testStory[]", array(0 => ''), 0, "class='form-control chosen'");?></td>
    <td><?php echo html::select("testPri[]", $lang->task->priList, '', "class='form-control chosen'");?></td>
    <td>
      <div class='input-group'>
        <?php echo html::input("testEstStarted[]", '', "class='form-control form-date' placeholder='{$lang->task->estStarted}'");?>
        <span class='input-group-addon fix-border'>~</span>
        <?php echo html::input("testDeadline[]", '', "class='form-control form-date' placeholder='{$lang->task->deadline}'");?>
      </div>
    </td>
    <td><?php echo html::select("testAssignedTo[]", array('' => ''), '', "class='form-control chosen'");?></td>
    <td><?php echo html::input("testEstimate[]", '', "class='form-control'");?></td>
    <td class='text-center'>
      <div class="btn-group">
        <button type="button" class="btn btn-sm" tabindex="-1" onclick='addItem(this)'><i class="icon icon-plus"></i></button>
        <button type="button" class="btn btn-sm" tabindex="-1" onclick='removeItem(this)'><i class="icon icon-close"></i></button>
      </div>
    </td>
  </tr>
</table>
<?php js::set('stories', $testStories);?>
<?php js::set('storyPinYin', (empty($config->isINT) and class_exists('common')) ? common::convert2Pinyin($testStories) : array());?>
<?php js::set('testStoryIdList', $testStoryIdList);?>
<?php js::set('executionID', $execution->id);?>
<?php js::set('executionType', $execution->type);?>
<?php js::set('newRowCount', 5);?>
<script>
$(function(){parent.$('body.hide-modal-close').removeClass('hide-modal-close');})
</script>
<?php include '../../common/view/footer.html.php';?>
