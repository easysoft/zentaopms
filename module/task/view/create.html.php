<?php
/**
 * The create view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
<?php js::set('holders', $lang->task->placeholder);?>
<div class='container'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['task']);?></span>
      <strong><small class='text-muted'><i class='icon icon-plus'></i></small> <?php echo $lang->task->create;?></strong>
    </div>
  </div>
  <form class='form-condensed' method='post' enctype='multipart/form-data' id='dataform' class='ajaxForm'>
    <table class='table table-form'> 
      <tr>
        <th class='w-100px'><?php echo $lang->task->project;?></th>
        <td colspan='3'><?php echo $project->name;?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->task->module;?></th>
        <td id='moduleIdBox' class='w-p35'><?php echo html::select('module', $moduleOptionMenu, $task->module, "class='form-control' onchange='setStories(this.value,$project->id)'");?></td><td class='w-p40'></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->task->assignedTo;?></th>
        <td><?php echo html::select('assignedTo[]', $members, $task->assignedTo, "class='form-control chosen'");?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->task->type;?></th>
        <td><?php echo html::select('type', $lang->task->typeList, $task->type, 'class=form-control onchange="setOwners(this.value)"');?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->task->story;?></th>
        <td colspan='2'>
          <?php echo html::select('story', $stories, $task->story, 'class=form-control onchange=setPreview();');?>
        </td><td><a href='#' id='preview' class='iframe'><i class='icon-eye-open'></i> <?php echo $lang->preview;?></a></td>
      </tr>  
      <tr>
        <th><?php echo $lang->task->name;?></th>
        <td colspan='2'>
          <?php echo html::input('name', $task->name, "class='form-control'");?>
        </td>
        <td><a href='#' id='copyButton' onclick='copyStoryTitle()'><i class='icon-copy'></i> <?php echo $lang->task->copyStoryTitle;?></a></td>
      </tr>  
      <tr>
        <th><?php echo $lang->task->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', $task->desc, "rows='7' class='form-control'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->task->pri;?></th>
        <td><?php echo html::select('pri', $lang->task->priList, $task->pri, 'class=form-control');?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->task->estimate;?></th>
        <td>
          <div class="input-group">
            <?php echo html::input('estimate', $task->estimate, "class='form-control'")?>
            <span class="input-group-addon"><?php echo $lang->task->hour;?></span>
          </div>
        </td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->task->estStarted;?></th>
        <td><?php echo html::input('estStarted', $task->estStarted, "class='form-control form-date'");?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->task->deadline;?></th>
        <td><?php echo html::input('deadline', $task->deadline, "class='form-control form-date'");?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->task->mailto;?></th>
        <td colspan='3'>
          <?php
          echo html::select('mailto[]', $users, str_replace(' ', '', $task->mailto), "multiple class='form-control'");
          if($contactLists) echo html::select('', $contactLists, '', "class='form-control' onchange=\"setMailto('mailto', this.value)\"");
          ?>
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
