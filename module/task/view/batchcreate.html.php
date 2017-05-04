<?php
/**
 * The batch create view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['task']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchCreate']);?></small> <?php echo $lang->task->batchCreate . $lang->task->common;?></strong>
    <span><small><a href='javascript:toggleZeroTaskStory();' id='zeroTaskStory'><?php echo $lang->story->zeroTask;?><i class='icon icon-remove'></i></a></small></span>
    <div class='actions'>
      <?php echo html::commonButton($lang->pasteText, "data-toggle='myModal'")?>
      <button type="button" class="btn btn-default" data-toggle="customModal"><i class='icon icon-cog'></i> </button>
    </div>
  </div>
</div>
<?php
$visibleFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field)$visibleFields[$field] = '';
}
$colspan     = count($visibleFields) + 3;
$hiddenStory = ((isonlybody() and $storyID) || $this->config->global->flow == 'onlyTask') ? ' hidden' : '';
if($hiddenStory and isset($visibleFields['story'])) $colspan -= 1;
?>
<form class='form-condensed' method='post' target='hiddenwin'>
  <table class='table table-form table-fixed with-border'>
    <thead>
      <tr class='text-center'>
        <th class='w-30px'><?php echo $lang->idAB;?></th> 
        <th class='w-150px<?php echo zget($visibleFields, 'module', ' hidden')?>'><?php echo $lang->task->module?></th>
        <th class='w-200px<?php echo zget($visibleFields, 'story', ' hidden'); echo $hiddenStory;?>'><?php echo $lang->task->story;?></th>
        <th><?php echo $lang->task->name;?> <span class='required'></span></th>
        <th class='w-80px'><?php echo $lang->typeAB;?> <span class='required'></span></th>
        <th class='w-150px<?php echo zget($visibleFields, 'assignedTo', ' hidden')?>'><?php echo $lang->task->assignedTo;?></th>
        <th class='w-50px<?php echo zget($visibleFields, 'estimate', ' hidden')?>'><?php echo $lang->task->estimateAB;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'estStarted', ' hidden')?>'><?php echo $lang->task->estStarted;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'deadline', ' hidden')?>'><?php echo $lang->task->deadline;?></th>
        <th class='w-p20<?php echo zget($visibleFields, 'desc', ' hidden')?>'><?php echo $lang->task->desc;?></th>
        <th class='w-70px<?php echo zget($visibleFields, 'pri', ' hidden')?>'><?php echo $lang->task->pri;?></th>
      </tr>
    </thead>

    <?php
    $stories['ditto'] = $lang->task->ditto; 
    $lang->task->typeList['ditto'] = $lang->task->ditto; 
    $members['ditto'] = $lang->task->ditto;
    $modules['ditto'] = $lang->task->ditto;
    ?>
    <?php for($i = 0; $i < $config->task->batchCreate; $i++):?>
    <?php 
    if($i == 0)
    {
        $currentStory = $storyID;
        $type         = '';
        $member       = '';
        $module       = $story ? $story->module : '';
    }
    else
    {
        $currentStory = $type = $member = $module = 'ditto';
    }
    ?>
    <?php $pri = 3;?>
    <tr>
      <td class='text-center'><?php echo $i+1;?></td>
      <td <?php echo zget($visibleFields, 'module', "class='hidden'")?> style='overflow:visible'><?php echo html::select("module[$i]", $modules, $module, "class='form-control chosen' onchange='setStories(this.value, $project->id, $i)'")?></td>
      <td <?php echo zget($visibleFields, 'story', "class='hidden'"); echo $hiddenStory;?> style='overflow: visible'>
        <div class='input-group'>
          <?php echo html::select("story[$i]", $stories, $currentStory, "class='form-control chosen' onchange='setStoryRelated($i)'");?>
          <span class='input-group-btn'>
            <a href='javascript:copyStoryTitle(<?php echo $i;?>)' class='btn' title='<?php echo $lang->task->copyStoryTitle; ?>'><i class='icon-angle-right'></i></a>
          </span>
        </div>
      </td>
      <td style='overflow:visible'>
        <div class='input-group'>
        <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='{$lang->task->colorTag}' data-update-text='#name\\[{$i}\\]'");?>
        <?php echo html::input("name[$i]", '', "class='form-control' autocomplete='off'");?>
        </div>
      </td>
      <td><?php echo html::select("type[$i]", $lang->task->typeList, $type, 'class=form-control');?></td>
      <td <?php echo zget($visibleFields, 'assignedTo', "class='hidden'")?> style='overflow:visible'><?php echo html::select("assignedTo[$i]", $members, $member, "class='form-control chosen'");?></td>
      <td <?php echo zget($visibleFields, 'estimate', "class='hidden'")?>><?php echo html::input("estimate[$i]", '', "class='form-control text-center' autocomplete='off'");?></td>
      <td <?php echo zget($visibleFields, 'estStarted', "class='hidden'")?>><?php echo html::input("estStarted[$i]", '', "class='form-control text-center form-date'");?></td>
      <td <?php echo zget($visibleFields, 'deadline', "class='hidden'")?>><?php echo html::input("deadline[$i]", '', "class='form-control text-center form-date'");?></td>
      <td <?php echo zget($visibleFields, 'desc', "class='hidden'")?>><?php echo html::textarea("desc[$i]", '', "rows='1' class='form-control autosize'");?></td>
      <td <?php echo zget($visibleFields, 'pri', "class='hidden'")?>><?php echo html::select("pri[$i]", (array)$lang->task->priList, $pri, 'class=form-control');?></td>
    </tr>
    <?php endfor;?>
    <tr><td colspan='<?php echo $colspan?>' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<table class='hide' id='trTemp'>
  <tbody>
    <tr>
      <td class='text-center'>%s</td>
      <td <?php echo zget($visibleFields, 'module', "class='hidden'")?> style='overflow:visible'><?php echo html::select("module[%s]", $modules, $module, "class='form-control' onchange='setStories(this.value, $project->id, \"%s\")'")?></td>
      <td <?php echo zget($visibleFields, 'story', "class='hidden'"); echo $hiddenStory;?> style='overflow: visible'>
        <div class='input-group'>
          <?php echo html::select("story[%s]", $stories, $currentStory, "class='form-control' onchange='setStoryRelated(\"%s\")'");?>
          <span class='input-group-btn'>
            <a href='javascript:copyStoryTitle("%s")' class='btn' title='<?php echo $lang->task->copyStoryTitle; ?>'><i class='icon-angle-right'></i></a>
          </span>
        </div>
      </td>
      <td style='overflow:visible'>
        <div class='input-group'>
        <?php echo html::hidden("color[%s]", '', "data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='{$lang->task->colorTag}' data-update-text='#name\\[%s\\]'");?>
        <?php echo html::input("name[%s]", '', "class='form-control' autocomplete='off'");?>
        </div>
      </td>
      <td><?php echo html::select("type[%s]", $lang->task->typeList, $type, 'class=form-control');?></td>
      <td <?php echo zget($visibleFields, 'assignedTo', "class='hidden'")?> style='overflow:visible'><?php echo html::select("assignedTo[%s]", $members, $member, "class='form-control'");?></td>
      <td <?php echo zget($visibleFields, 'estimate', "class='hidden'")?>><?php echo html::input("estimate[%s]", '', "class='form-control text-center' autocomplete='off'");?></td>
      <td <?php echo zget($visibleFields, 'estStarted', "class='hidden'")?>><?php echo html::input("estStarted[%s]", '', "class='form-control text-center form-date'");?></td>
      <td <?php echo zget($visibleFields, 'deadline', "class='hidden'")?>><?php echo html::input("deadline[%s]", '', "class='form-control text-center form-date'");?></td>
      <td <?php echo zget($visibleFields, 'desc', "class='hidden'")?>><?php echo html::textarea("desc[%s]", '', "rows='1' class='form-control autosize'");?></td>
      <td <?php echo zget($visibleFields, 'pri', "class='hidden'")?>><?php echo html::select("pri[%s]", (array)$lang->task->priList, $pri, 'class=form-control');?></td>
    </tr>
  </tbody>
</table>
<?php js::set('storyTasks', $storyTasks);?>
<?php js::set('mainField', 'name');?>
<?php js::set('ditto', $lang->task->ditto);?> 
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=task&section=custom&key=batchCreateFields')?>
<?php include '../../common/view/customfield.html.php';?>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
