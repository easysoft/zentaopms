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
<?php js::set('toTaskList', $this->config->global->flow == 'onlyTask' || !empty($task->id));?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->task->create;?></h2>
      <div class='btn-toolbar pull-right'>
        <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=task&section=custom&key=createFields')?>
        <?php include '../../common/view/customfield.html.php';?>
      </div>
    </div>
    <form class='load-indicator main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'> 
        <tr>
          <th><?php echo $lang->task->type;?></th>
          <td><?php echo html::select('type', $lang->task->typeList, $task->type, "class='form-control chosen' onchange='setOwners(this.value)' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->task->module;?></th>
          <td id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $task->module, "class='form-control chosen' onchange='setStories(this.value,$project->id)'");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->task->assignedTo;?></th>
          <td>
            <div class="input-group" id="dataPlanGroup">
              <?php echo html::select('assignedTo[]', $members, $task->assignedTo, "class='form-control chosen'");?>
              <?php echo html::input('teamMember', '', "class='form-control team-group fix-border hidden' readonly='readonly'");?>
              <span class="input-group-addon team-group hidden" data-toggle='modalTeam'><?php echo $lang->task->team;?></span>
              <label class='input-group-addon affair'><input type='checkBox' name='multiple' id="multipleBox" value='1'/> <?php echo $lang->task->multipleAB;?></label>
              <span class='input-group-btn <?php if($task->type !== 'affair') echo 'hidden';?>' id='selectAllUser'>
                <button type='button' class='btn'>
                  <?php echo $lang->task->selectAllUser;?>
                </button>
              </span>
            </div>
          </td>
          <td>
          </td>
        </tr>
        <?php if(strpos(",$showFields,", ',story,') !== false and $this->config->global->flow != 'onlyTask' and $project->type != 'ops'):?>
        <tr>
          <th><?php echo $lang->task->story;?></th>
          <td colspan='3'>
            <?php if(empty($stories)):?>
            <span id='story'><?php printf($lang->task->noticeLinkStory, html::a($this->createLink('project', 'linkStory', "projectID=$project->id"), $lang->project->linkStory, '_blank'), html::a("javascript:loadStories($project->id)", $lang->refresh));?></span>
            <?php else:?>
            <div class='input-group'>
              <?php echo html::select('story', $stories, $task->story, "class='form-control chosen' onchange='setStoryRelated();'");?>
              <span class='input-group-btn' id='preview'><a href='#' class='btn iframe'><?php echo $lang->preview;?></a></span>
            </div>
            <?php endif;?>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->task->name;?></th>
          <td colspan='3'>
            <div class="input-control has-icon-left has-icon-right">
              <div class="colorpicker">
                <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                <ul class="dropdown-menu clearfix">
                  <li class="heading"><?php echo $lang->task->colorTag;?><i class="icon icon-close"></i></li>
                </ul>
                <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-left" data-update-color="#title"  data-provide="colorpicker">
              </div>
              <?php echo html::input('name', $task->name, "class='form-control' autocomplete='off' required");?>
              <?php if($this->config->global->flow != 'onlyTask'):?>
              <a href='javascript:copyStoryTitle();' id='copyButton' class='input-control-icon-right'><?php echo $lang->task->copyStoryTitle;?></a>
              <?php echo html::hidden("storyEstimate") . html::hidden("storyDesc") . html::hidden("storyPri");?>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <?php if(strpos(",$showFields,", ',pri,') !== false):?>
        <tr>
          <th><?php echo $lang->task->pri;?></th>
          <td colspan='2'>
            <?php
            $hasCustomPri = false;
            foreach($lang->task->priList as $priKey => $priValue)
            {
                if($priKey and $priValue and ($priKey != $priValue or strlen($priKey) != strlen($priValue)))
                {
                    $hasCustomPri = true;
                    break;
                }
            }
            $priList = $lang->task->priList;
            if(end($priList))
            {
                unset($priList[0]);
                $priList[0] = '';
            }
            ?>
            <?php if($hasCustomPri):?>
            <?php echo html::select('pri', (array)$priList, $task->pri, "class='form-control chosen'");?> 
            <?php else: ?>
            <?php echo html::select('pri', (array)$priList, $task->pri, "class='form-control' data-provide='labelSelector'");?> 
            <?php endif; ?>
           </td>
         </tr>
         <?php endif;?>
         <?php if(strpos(",$showFields,", ',estimate,') !== false):?>
         <tr>
           <th><?php echo $lang->task->estimateAB;?></th>
           <td><input type="number" min="0" step="0.5" name="estimate" id="estimate" value="<?php echo $task->estimate;?>" class="form-control" autocomplete="off"></td>
           <td class="muted"><?php echo $lang->task->hour;?></td>
         </tr>
         <?php endif;?>
        <tr>
          <th><?php echo $lang->task->desc;?></th>
          <td colspan='3'><?php echo html::textarea('desc', $task->desc, "rows='10' class='form-control'");?></td>
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
        <tr <?php echo $this->config->global->flow == 'onlyTask' ? "class='hidden'" : '';?>>
          <th><?php echo $lang->task->afterSubmit;?></th>
          <td colspan='3'><?php echo html::radio('after', $lang->task->afterChoices, $this->config->global->flow == 'onlyTask' || !empty($task->id) ? 'toTaskList' : 'continueAdding');?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center'><?php echo html::submitButton('', '', 'btn btn-primary btn-wide') . html::backButton('', '', 'btn btn-gray btn-wide');?></td>
        </tr>
      </table>

      <div class='modal fade modal-team' id='modalTeam'>
        <div class='modal-dialog'>
          <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal'>
              <span aria-hidden='true'>×</span><span class='sr-only'><?php echo $lang->task->close;?></span>
            </button>
            <h4 class='modal-title'><?php echo $lang->task->team;?></h4>
          </div>
          <div class='modal-content with-padding'>
            <table class="table table-form">
              <?php for($i = 0; $i < 6; $i++):?>
              <tr>
                <td><?php echo html::select("team[]", $members, '', "class='form-control chosen'");?></td>
                <td>
                  <div class='input-group'>
                    <?php echo html::input("teamEstimate[]", '', "class='form-control text-center' placeholder='{$lang->task->estimateAB}'") ?>
                    <span class='input-group-addon'><?php echo $lang->task->hour;?></span>
                  </div>
                </td>
                <td class='w-90px'>
                  <a href='javascript:;' class='btn btn-move-up btn-sm'><i class='icon-arrow-up'></i></a>
                  <a href='javascript:;' class='btn btn-move-down btn-sm'><i class='icon-arrow-down'></i></a>
                </td>
              </tr>
              <?php endfor;?>
              <tr>
                <td colspan='3' class='text-center'><?php echo html::a('javascript:void(0)', $lang->confirm, '', "class='btn btn-primary' data-dismiss='modal'") ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
