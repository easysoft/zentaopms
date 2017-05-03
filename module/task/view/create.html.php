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
<?php include '../../common/view/form.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['task']);?></span>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->task->create;?></strong>
    </div>
    <div class='actions'>
      <button type="button" class="btn btn-default" data-toggle="customModal"><i class='icon icon-cog'></i> </button>
    </div>
  </div>
  <form class='form-condensed' method='post' enctype='multipart/form-data' id='dataform' data-type='ajax'>
    <table class='table table-form'> 
      <tr>
        <th class='w-100px'><?php echo $lang->task->module;?></th>
        <td id='moduleIdBox' class='w-p25-f'><?php echo html::select('module', $moduleOptionMenu, $task->module, "class='form-control chosen' onchange='setStories(this.value,$project->id)'");?></td>
        <td></td><td></td><td></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->task->type;?></th>
        <td><?php echo html::select('type', $lang->task->typeList, $task->type, 'class=form-control onchange="setOwners(this.value)"');?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->task->assignedTo;?></th>
        <td><?php echo html::select('assignedTo[]', $members, $task->assignedTo, "class='form-control chosen'");?></td>
        <td>
          <button type='button' class='btn btn-link<?php echo $task->type == 'affair' ? '' : ' hidden'?>' id='selectAllUser'><?php echo $lang->task->selectAllUser ?></button>
        </td>
      </tr>
      <?php if(strpos(",$showFields,", ',story,') !== false and $this->config->global->flow != 'onlyTask'):?>
      <tr>
        <th><?php echo $lang->task->story;?></th>
        <td colspan='5'>
          <div class='input-group'>
            <?php if(empty($stories)):?>
            <span id='story'><?php printf($lang->task->noticeLinkStory, html::a($this->createLink('project', 'linkStory', "projectID=$project->id"), $lang->project->linkStory, '_blank'), html::a("javascript:loadStories($project->id)", $lang->refresh));?></span>
            <?php else:?>
            <?php echo html::select('story', $stories, $task->story, "class='form-control chosen' onchange='setStoryRelated();'");?>
            <span class='input-group-btn' id='preview'><a href='#' class='btn iframe'><?php echo $lang->preview;?></a></span>
            <?php endif;?>
          </div>
        </td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->task->name;?></th>
        <td colspan='5'>
          <div class='row-table'>
            <div class='col-table'>
              <div class="input-group w-p100">
                <input type='hidden' id='color' name='color' data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='<?php echo $lang->task->colorTag ?>' data-update-text='#name'>
                <?php echo html::input('name', $task->name, "class='form-control' autocomplete='off'");?>
                <?php if($this->config->global->flow != 'onlyTask'):?>
                <span class='input-group-btn'><a href='javascript:copyStoryTitle();' id='copyButton' class='btn'><?php echo $lang->task->copyStoryTitle;?></a></span>
                <?php endif;?>
              </div>
            </div>
            <?php
            $hiddenPri = strpos(",$showFields,", ',pri,') === false;
            $hiddenEst = strpos(",$showFields,", ',estimate,') === false;
            ?>
            <?php if(!$hiddenPri):?>
            <?php
            $hasCustomPri = false;
            foreach($lang->task->priList as $priKey => $priValue)
            {
                if($priKey != $priValue)
                {
                    $hasCustomPri = true;
                    break;
                }
            }
            ?>
            <div class='col-table<?php if($hasCustomPri) echo ' has-custom-pri';?>' id='priRowCol'>
              <div class='input-group'>
                <span class='input-group-addon fix-border br-0'><?php echo $lang->task->pri;?></span>
                <?php if($hasCustomPri):?>
                <?php echo html::select('pri', $lang->task->priList, '', "class='form-control'");?> 
                <?php else: ?>
                <div class='input-group-btn dropdown-pris'>
                  <button type='button' class='btn dropdown-toggle br-0' data-toggle='dropdown'>
                    <span class='pri-text'></span> &nbsp;<span class='caret'></span>
                  </button>
                  <ul class='dropdown-menu pull-right'></ul>
                  <?php echo html::select('pri', $lang->task->priList, '', "class='hide'");?>
                </div>
                <?php endif; ?>
              </div>
            </div>
            <?php endif; ?>

            <?php if(!$hiddenEst):?>
            <div class='col-table' id='estRowCol'>
              <div class='input-group'>
                <span class='input-group-addon fix-border br-0'><?php echo $lang->task->estimateAB;?></span>
                <?php echo html::input('estimate', '', "class='form-control' placeholder='{$lang->task->hour}' autocomplete='off'");?>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->task->desc;?></th>
        <td colspan='5'><?php echo html::textarea('desc', $task->desc, "rows='10' class='form-control'");?></td>
      </tr>
      <?php
      $hiddenEstStarted = strpos(",$showFields,", ',estStarted,') === false;
      $hiddenDeadline   = strpos(",$showFields,", ',deadline,')   === false;
      $hiddenMailto     = strpos(",$showFields,", ',mailto,')     === false;
      ?>
      <?php if(!$hiddenEstStarted or !$hiddenDeadline or !$hiddenMailto):?>
      <tr>
        <th><?php echo ($hiddenEstStarted and $hiddenDeadline) ? $lang->task->mailto : $lang->task->datePlan;?></th>
        <td id='planAndMailCell' colspan='5' class='<?php if(!$hiddenMailto) echo 'has-mail-col' ?>'>
            <div class='row-table'>
              <?php if(!$hiddenEstStarted or !$hiddenDeadline):?>
              <div class='col-table' id='taskPlanCol'>
                <div class='input-group' id='dataPlanGroup'>
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
              </div>
              <?php endif;?>
              <?php if(!$hiddenMailto):?>
              <div class='col-table' id='mailCol'>
                <div id='mailtoGroup' class='input-group'>
                  <?php if(!$hiddenEstStarted or !$hiddenDeadline):?>
                  <span class='input-group-addon'><?php echo $lang->task->mailto;?></span>
                  <?php endif;?>
                  <?php echo html::select('mailto[]', $project->acl == 'private' ? $members : $users, str_replace(' ', '', $task->mailto), "multiple class='form-control'");?>
                  <?php echo $this->fetch('my', 'buildContactLists');?>
                </div>
              </div>
              <?php endif;?>
            </div>
        </td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->files;?></th>
        <td colspan='5'><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>
      <tr <?php echo $this->config->global->flow == 'onlyTask' ? "class='hidden'" : '';?>>
        <th><?php echo $lang->task->afterSubmit;?></th>
        <td colspan='5'><?php echo html::radio('after', $lang->task->afterChoices, $this->config->global->flow == 'onlyTask' ? 'toTaskList' : 'continueAdding');?></td>
      </tr>
      <tr>
        <td></td>
        <td colspan='5'><?php echo html::submitButton() . html::backButton();?></td>
      </tr>
    </table>
    <span id='responser'></span>
  </form>
</div>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=task&section=custom&key=createFields')?>
<?php include '../../common/view/customfield.html.php';?>
<?php include '../../common/view/footer.html.php';?>
