<?php
/**
 * The create task view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<tr>
  <th><?php echo $lang->issue->resolution;?></th>
  <td>
    <?php echo html::select('resolution', $lang->issue->resolveMethods, $resolution, 'class="form-control chosen" onchange="getSolutions()"');?>
  </td>
</tr>
<tr>
  <th><?php echo $lang->task->project;?></th>
  <td class="required"><?php echo html::select('project', $projects, $projectID, "class='form-control chosen' onchange='loadAll(this.value)'");?></td><td></td><td></td>
</tr>
<tr>
  <th><?php echo $lang->task->type;?></th>
  <td class="required"><?php echo html::select('type', $lang->task->typeList, $task->type, "class='form-control chosen'");?></td>
  <td>
  </td>
</tr>
<tr>
  <th><?php echo $lang->task->module;?></th>
  <td id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $task->module, "class='form-control chosen'");?></td>
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
      <span class="input-group-btn team-group hidden"><a class="btn br-0" href="#modalTeam" data-toggle="modal"><?php echo $lang->task->team;?></a></span>
    </div>
  </td>
</tr>
<tr>
  <th><?php echo $lang->task->name;?></th>
  <td colspan='3'>
    <div class="input-group title-group">
      <div class="input-control has-icon-right required">
        <?php echo html::input('name', $task->name, "class='form-control'");?>
      </div>
      <span class="input-group-addon fix-border br-0"><?php echo $lang->task->pri;?></span>
      <div class="input-group-btn pri-selector" data-type="pri">
        <?php echo html::select('pri', $lang->task->priList, $issue->pri, "class='form-control'");?>
      </div>
      <div class='table-col w-120px'>
        <div class="input-group">
          <span class="input-group-addon fix-border br-0"><?php echo $lang->task->estimateAB;?></span>
          <input type="text" name="estimate" id="estimate" value="<?php echo $task->estimate;?>" class="form-control" autocomplete="off">
        </div>
      </div>
    </div>
  </td>
</tr>
<tr>
  <th><?php echo $lang->task->desc;?></th>
  <td colspan='3'>
    <?php echo html::textarea('desc', $task->desc, "rows='10' class='form-control'");?>
  </td>
</tr>
<?php
$hiddenEstStarted = strpos(",$showFields,", ',estStarted,') === false;
$hiddenDeadline   = strpos(",$showFields,", ',deadline,')   === false;
?>
<?php if(!$hiddenEstStarted or !$hiddenDeadline):?>
<tr>
  <th><?php echo $lang->task->datePlan;?></th>
  <td colspan='2'>
    <div class='input-group required'>
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
<tr>
  <th><?php echo $lang->issue->resolvedBy;?></th>
  <td>
    <?php echo html::select('resolvedBy', $users, $this->app->user->account, "class='form-control chosen'");?>
  </td>
</tr>
<tr>
  <th><?php echo $lang->issue->resolvedDate;?></th>
  <td>
     <div class='input-group has-icon-right'>
       <?php echo html::input('resolvedDate', date('Y-m-d'), "class='form-control form-date'");?>
       <label for="date" class="input-control-icon-right"><i class="icon icon-delay"></i></label>
     </div>
  </td>
</tr>
<tr>
  <td></td>
  <td>
    <?php echo html::hidden('status', 'wait');?>
    <div class='form-action'><?php echo html::submitButton();?></div>
  </td>
</tr>
<script>
/**
 * Load module and members.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function loadAll(projectID)
{
    var moduleID = $('#moduleIdBox #module').val();
    var extra    = $(this).prop('checked') ? 'allModule' : '';
    $('#moduleIdBox').load(createLink('tree', 'ajaxGetOptionMenu', "rootID=" + projectID + '&viewType=task&branch=0&rootModuleID=0&returnType=html&fieldID=&needManage=0&extra=' + extra), function()
    {
        $('#moduleIdBox #module').val(moduleID).attr('onchange', "setStories(this.value, " + projectID + ")").chosen();
    });

    loadProjectMembers(projectID);
}

/**
 * Load team members of the project.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function loadProjectMembers(projectID)
{
    $.get(createLink('project', 'ajaxGetMembers', 'projectID=' + projectID + '&assignedTo=' + $('#assignedTo').val()), function(data)
    {
        $('#assignedTo_chosen').remove();
        $('#assignedTo').next('.picker').remove();
        $('#assignedTo').replaceWith(data);
        $('#assignedTo').attr('name', 'assignedTo[]').chosen();
    });
}
</script>
