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
  </div>
  <form class='form-condensed' method='post' enctype='multipart/form-data' id='dataform' data-type='ajax'>
    <table class='table table-form'> 
      <tr>
        <th class='w-100px'><?php echo $lang->task->module;?></th>
        <td id='moduleIdBox' class='w-p25-f'><?php echo html::select('module', $moduleOptionMenu, $task->module, "class='form-control chosen' onchange='setStories(this.value,$project->id)'");?></td>
        <td class='w-p25-f'></td><td></td>
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
      <tr>
        <th><?php echo $lang->task->story;?></th>
        <td colspan='3'>
          <div class='input-group'>
            <?php echo html::select('story', $stories, $task->story, "class='form-control chosen' onchange='setStoryRelated();'");?>
            <span class='input-group-btn' id='preview'><a href='#' class='btn iframe'><?php echo $lang->preview;?></a></span>
          </div>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->task->name;?></th>
        <td colspan='3'>
          <div class='row'>
            <div class='col-sm-8'>
              <?php echo html::input('name', $task->name, "class='form-control'");?>
              <span class='input-group-btn'><a href='javascript:copyStoryTitle();' id='copyButton' class='btn'><?php echo $lang->task->copyStoryTitle;?></a></span>
            </div>
            <div class='col-sm-2'>
              <div class="input-group">
                <span class='input-group-addon fix-border'><?php echo $lang->task->pri;?></span>
                <?php echo html::select('pri', $lang->task->priList, $task->pri, 'class=form-control');?>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class="input-group">
                <span class='input-group-addon fix-border'><?php echo $lang->task->estimate;?></span>
                <?php echo html::input('estimate', $task->estimate, "class='form-control' autocomplete='off' placeholder='{$lang->task->hour}'")?>
              </div>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->task->desc;?></th>
        <td colspan='3'><?php echo html::textarea('desc', $task->desc, "rows='10' class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->task->estStarted;?></th>
        <td><?php echo html::input('estStarted', $task->estStarted, "class='form-control form-date'");?></td>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->task->deadline;?></span>
            <?php echo html::input('deadline', $task->deadline, "class='form-control form-date'");?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->task->mailto;?></th>
        <td colspan='3'>
          <div class='input-group'>
            <?php echo html::select('mailto[]', $project->acl == 'private' ? $members : $users, str_replace(' ', '', $task->mailto), "multiple class='form-control'");?>
            <?php if($contactLists) echo html::select('', $contactLists, '', "class='form-control chosen' onchange=\"setMailto('mailto', this.value)\"");?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->files;?></th>
        <td colspan='3'><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->task->afterSubmit;?></th>
        <td colspan='3'><?php echo html::radio('after', $lang->task->afterChoices, 'continueAdding');?></td>
      </tr>
      <tr>
        <td></td>
        <td colspan='3'><?php echo html::submitButton() . html::backButton();?></td>
      </tr>
    </table>
    <span id='responser'></span>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
