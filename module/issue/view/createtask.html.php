<?php
/**
 * The createtask view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
        <tr class='taskTR'>
          <th><?php echo $lang->task->project;?></th>
          <td><?php echo html::select('project', $projects, $project->id, "class='form-control chosen' onchange='loadAll(this.value)'");?></td><td></td><td></td>
        </tr>
        <tr class='taskTR'>
          <th><?php echo $lang->task->type;?></th>
          <td><?php echo html::select('type', $lang->task->typeList, $task->type, "class='form-control chosen'");?></td>
          <td>
            <div class="checkbox-primary hidden" id='selectTestStoryBox'>
              <input type="checkbox" name='selectTestStory' id="selectTestStory" value='1' onchange='toggleSelectTestStory()' /><label for="selectTestStory" class="no-margin"><?php echo $lang->task->selectTestStory;?></label>
            </div>
          </td>
        </tr>
        <tr class='taskTR'>
          <th><?php echo $lang->task->module;?></th>
          <td id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $task->module, "class='form-control chosen' onchange='setStories(this.value, $project->id)'");?></td>
          <td>
            <div class="checkbox-primary">
              <input type="checkbox" id="showAllModule" <?php if($showAllModule) echo 'checked';?>><label for="showAllModule" class="no-margin"><?php echo $lang->task->allModule;?></label>
            </div>
          </td>
          <td></td>
        </tr>
        <tr class='taskTR'>
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
        <?php if($stories and $project->type != 'ops'):?>
        <tr id='testStoryBox' class='hidden'>
          <th><?php echo $lang->task->selectTestStory;?></th>
          <td colspan='3'>
            <table class='table table-form mg-0 table-bordered'>
              <thead>
                <tr class='taskTR'>
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
                <?php if($i == 0):?>
                <tr class='taskTR'>
                  <td><?php echo html::select("testStory[]", $stories, '', "class='form-control chosen'");?></td>
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
                <?php endif;?>
              </tbody>
            </table>
          </td>
        </tr>
        <?php endif;?>
        <tr class='taskTR'>
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
                <?php echo html::input('name', $task->name, "class='form-control'");?>
                <a href='javascript:copyStoryTitle();' id='copyButton' class='input-control-icon-right'><?php echo $lang->task->copyStoryTitle;?></a>
                <?php echo html::hidden("storyEstimate") . html::hidden("storyDesc") . html::hidden("storyPri");?>
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
        <tr class='taskTR'>
          <th><?php echo $lang->task->desc;?></th>
          <td colspan='3'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=task&link=desc');?>
            <?php echo html::textarea('desc', $task->desc, "rows='10' class='form-control'");?>
          </td>
        </tr>
        <tr class='taskTR'>
          <th><?php echo $lang->files;?></th>
          <td colspan='3'><?php echo $this->fetch('file', 'buildform');?></td>
        </tr>
        <?php
        $hiddenEstStarted = strpos(",$showFields,", ',estStarted,') === false;
        $hiddenDeadline   = strpos(",$showFields,", ',deadline,')   === false;
        ?>
        <?php if(!$hiddenEstStarted or !$hiddenDeadline):?>
        <tr class='taskTR'>
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
