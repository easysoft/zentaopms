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
<form method='post' enctype='multipart/form-data' id='dataform' class='ajaxForm'>
  <table align='center' class='table-1 a-left'> 
    <caption><?php echo $lang->task->create;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->task->project;?></th>
      <td><?php echo $project->name;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->module;?></th>
      <td><span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $task->module, "class='select-3' onchange='setStories(this.value,$project->id)'");?></span></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->assignedTo;?></th>
      <td><?php echo html::select('assignedTo[]', $members, $task->assignedTo, 'class=select-3');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->type;?></th>
      <td><?php echo html::select('type', $lang->task->typeList, $task->type, 'class=select-3 onchange="setOwners(this.value)"');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->story;?></th>
      <td>
        <?php echo html::select('story', $stories, $task->story, 'class=select-1 onchange=setPreview();');?>
        <a href='' id='preview' class='iframe'><?php echo $lang->preview;?></a>
      </td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->name;?></th>
      <td>
      <?php
      echo html::input('name', $task->name, "class='text-1'");
      echo html::commonButton($lang->task->copyStoryTitle, 'onclick=copyStoryTitle() id="copyButton"');?>
      </td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->desc;?></th>
      <td><?php echo html::textarea('desc', $task->desc, "rows='7' class='area-1'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->pri;?></th>
      <td><?php echo html::select('pri', $lang->task->priList, $task->pri, 'class=select-3');?> 
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->estimate;?></th>
      <td><?php echo html::input('estimate', $task->estimate, "class='text-3'") . $lang->task->hour;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->estStarted;?></th>
      <td><?php echo html::input('estStarted', $task->estStarted, "class='text-3 date'");?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->deadline;?></th>
      <td><?php echo html::input('deadline', $task->deadline, "class='text-3 date'");?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->mailto;?></th>
      <td>
        <?php
        echo html::select('mailto[]', $users, str_replace(' ', '', $task->mailto), 'class="text-1" multiple');
        if($contactLists) echo html::select('', $contactLists, '', "class='f-right' onchange=\"setMailto('mailto', this.value)\"");
        ?>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->files;?></th>
      <td class='a-left'><?php echo $this->fetch('file', 'buildform');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->afterSubmit;?></th>
      <td><?php echo html::radio('after', $lang->task->afterChoices, 'continueAdding');?></td> 
    </tr>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::backButton();?></td>
    </tr>
  </table>
  <span id='responser'></span>
</form>
<?php include '../../common/view/footer.html.php';?>
