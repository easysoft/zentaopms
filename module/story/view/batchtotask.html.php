<?php
/**
 * The batch to task view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     story
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('ditto', $lang->task->ditto);?>
<?php js::set('storyTasks', $storyTasks);?>
<?php js::set('storyType', 'story');?>
<?php js::set('storyCount', count($stories));?>
<?php
$requiredFields = array();
foreach(explode(',', $config->task->create->requiredFields) as $field)
{
    if($field) $requiredFields[$field] = '';
}
?>
<div id="mainContent" class="main-content fade">
  <div class="main-header clearfix">
    <h2 class="pull-left"><?php echo $lang->story->batchToTask;?></h2>
  </div>
  <form method='post' class='batch-actions-form' target='hiddenwin' enctype='multipart/form-data' id="batchToTaskForm">
    <div class="table-responsive">
      <table class="table table-form" id="tableBody">
        <thead>
          <tr>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='c-module<?php echo zget($requiredFields, 'module', '', ' required');?>'><?php echo $lang->task->module?></th>
            <th class='c-story<?php echo zget($requiredFields, 'story', '', ' required');?>'><?php echo $lang->task->story;?></th>
            <th class='c-name required has-btn'><?php echo $lang->task->name;?></span></th>
            <th class='c-type required'><?php echo $lang->typeAB;?></span></th>
            <th class='c-assigned<?php echo zget($requiredFields, 'assignedTo', '', ' required');?>'><?php echo $lang->task->assignedTo;?></th>
            <th class='c-estimate<?php echo zget($requiredFields, 'estimate', '', ' required');?>'><?php echo $lang->task->estimateAB;?></th>
            <th class='c-date<?php echo zget($requiredFields, 'estStarted', '', ' required');?>'><?php echo $lang->task->estStarted;?></th>
            <th class='c-date<?php echo zget($requiredFields, 'deadline',   '', ' required');?>'><?php echo $lang->task->deadline;?></th>
            <th class='c-pri<?php echo zget($requiredFields, 'pri', '', ' required');?>'><?php echo $lang->task->pri;?></th>
            <th class='c-actions'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;?>
          <?php foreach($stories as $storyID => $story):?>
          <?php
          if(strpos('draft,closed', $story->status) !== false) continue;

          $currentStory = $storyID;
          $pri          = 3;
          $storyPairs   = zget($storyGroup, $story->module, array());
          $estimate     = $hourPointValue ? $story->estimate * $hourPointValue : $story->estimate;
          if($i == 1)
          {
              $moduleID = 0;
              $type = $assignedTo = '';
          }
          else
          {
              $type = $assignedTo = $moduleID = 'ditto';
              $storyPairs['ditto'] = $lang->task->ditto;
              $members['ditto']    = $lang->task->ditto;
              $modules['ditto']    = $lang->task->ditto;
              $lang->task->typeList['ditto'] = $lang->task->ditto;
          }

          if(in_array('module', $syncFields)) $moduleID = $story->module;
          if(in_array('pri', $syncFields)) $pri = $story->pri;
          if(in_array('assignedTo', $syncFields)) $assignedTo = $story->assignedTo ? $story->assignedTo : $assignedTo;
          ?>
          <tr>
            <td class='text-left c-id'><?php echo $i;?></td>
            <td style='overflow:visible'>
              <?php echo html::select("module[$i]", $modules, $moduleID, "class='form-control chosen' onchange='setStories(this.value, $executionID, $i)'")?>
            </td>
            <td style='overflow: visible'>
              <div class='input-group'>
                <?php echo html::select("story[$i]", $storyPairs, $currentStory, "class='form-control chosen' onchange='setStoryRelated($i)'");?>
                <span class='input-group-btn'>
                  <a id='preview<?php echo $i;?>' href="<?php echo $this->createLink('story', 'view', "storyID=$currentStory", '', true)?>" class='btn iframe btn-link btn-icon btn-copy' data-width='80%' title='<?php echo $lang->preview;?>'><i class='icon-eye'></i></a>
                  <a href='javascript:copyStoryTitle(<?php echo $i;?>)' class='btn btn-link btn-icon btn-copy' title='<?php echo $lang->task->copyStoryTitle; ?>'><i class='icon-arrow-right'></i></a>
                </span>
              </div>
            </td>
            <td style='overflow:visible'>
              <div class="input-control has-icon-right">
                <?php echo html::input("name[$i]", $story->title, "class='form-control title-import'");?>
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix pull-right">
                    <li class="heading"><?php echo $lang->task->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#name\\[$i\\]'");?>
                </div>
              </div>
            </td>
            <td><?php echo html::select("type[$i]", $lang->task->typeList, $taskType, 'class=form-control');?></td>
            <td style='overflow:visible'><?php echo html::select("assignedTo[$i]", $members, $assignedTo, "class='form-control chosen'");?></td>
            <td><?php echo html::input("estimate[$i]", $estimate, "class='form-control text-center'");?></td>
            <td>
              <div class='input-group'>
                <?php
                echo html::input("estStarted[$i]", '', "class='form-control text-center form-date' onkeyup='toggleCheck(this)'");
                if($i != 1) echo "<span class='input-group-addon estStartedBox'><input type='checkbox' name='estStartedDitto[$i]' id='estStartedDitto$i' " . ($i > 1 ? "checked" : '') . " /> {$lang->task->ditto}</span>";
                ?>
              </div>
            </td>
            <td>
              <div class='input-group'>
                <?php
                echo html::input("deadline[$i]", '', "class='form-control text-center form-date' onkeyup='toggleCheck(this)'");
                if($i != 1) echo "<span class='input-group-addon deadlineBox'><input type='checkbox' name='deadlineDitto[$i]' id='deadlineDitto$i' " . ($i > 1 ? "checked" : '') . " /> {$lang->task->ditto}</span>";
                ?>
              </div>
            </td>
            <td><?php echo html::select("pri[$i]", (array)$lang->task->priList, $pri, 'class=form-control');?></td>
            <td class='c-actions text-left'>
              <a href='javascript:;' onclick='addRow(this)' class='btn btn-link'><i class='icon-plus'></i></a>
              <?php if($i > 1):?>
              <a href='javascript:;' onclick='deleteRow(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
              <?php endif;?>
            </td>
          </tr>
          <?php $i ++;?>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='11' class='text-center form-actions'>
              <?php echo html::hidden('syncFields', implode(',', $syncFields));?>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php js::set('rowIndex', -- $i);?>
<div>
  <?php $i = '%i%';?>
  <table class='hidden'>
    <tr id='addRow' class='hidden'>
      <td class='text-left c-id'><?php echo $i;?></td>
      <td style='overflow:visible'>
        <?php echo html::select("module[$i]", $modules, 'ditto', "class='form-control chosen' onchange='setStories(this.value, $executionID, $i)'")?>
      </td>
      <td style='overflow: visible'>
        <div class='input-group'>
          <?php echo html::select("story[$i]", $storyPairs, 'ditto', "class='form-control chosen' onchange='setStoryRelated($i)'");?>
          <span class='input-group-btn'>
            <a id='preview<?php echo $i;?>' href='#' class='btn iframe btn-link btn-icon btn-copy' style='pointer-events:none' data-width='80%' disabled='disabled' title='<?php echo $lang->preview; ?>'><i class='icon-eye'></i></a>
            <a href='javascript:copyStoryTitle(<?php echo $i;?>)' class='btn btn-link btn-icon btn-copy' title='<?php echo $lang->task->copyStoryTitle; ?>'><i class='icon-arrow-right'></i></a>
          </span>
        </div>
      </td>
      <td style='overflow:visible'>
        <div class="input-control has-icon-right">
          <?php echo html::input("name[$i]", '', "class='form-control title-import'");?>
          <div class="colorpicker">
            <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
            <ul class="dropdown-menu clearfix pull-right">
              <li class="heading"><?php echo $lang->task->colorTag;?><i class="icon icon-close"></i></li>
            </ul>
            <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#name\\[$i\\]'");?>
          </div>
        </div>
      </td>
      <td><?php echo html::select("type[$i]", $lang->task->typeList, 'ditto', 'class=form-control');?></td>
      <td style='overflow:visible'><?php echo html::select("assignedTo[$i]", $members, 'ditto', "class='form-control chosen'");?></td>
      <td><?php echo html::input("estimate[$i]", '', "class='form-control text-center'");?></td>
      <td>
        <div class='input-group'>
          <?php
          echo html::input("estStarted[$i]", '', "class='form-control text-center form-date' onkeyup='toggleCheck(this)'");
          echo "<span class='input-group-addon estStartedBox'><input type='checkbox' name='estStartedDitto[$i]' id='estStartedDitto$i' checked/> {$lang->task->ditto}</span>";
          ?>
        </div>
      </td>
      <td>
        <div class='input-group'>
          <?php
          echo html::input("deadline[$i]", '', "class='form-control text-center form-date' onkeyup='toggleCheck(this)'");
          echo "<span class='input-group-addon deadlineBox'><input type='checkbox' name='deadlineDitto[$i]' id='deadlineDitto$i' checked/> {$lang->task->ditto}</span>";
          ?>
        </div>
      </td>
      <td><?php echo html::select("pri[$i]", (array)$lang->task->priList, $pri, 'class=form-control');?></td>
      <td class='c-actions text-left'>
        <a href='javascript:;' onclick='addRow(this)' class='btn btn-link'><i class='icon-plus'></i></a>
        <a href='javascript:;' onclick='deleteRow(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
      </td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
