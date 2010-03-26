<?php
/**
 * The create view of task module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorbox.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<script language='javascript'>
/* 拷贝需求标题为任务标题。*/
function copyStoryTitle()
{
    var storyTitle = $('#story option:selected').text();
    storyTitle = storyTitle.substr(storyTitle.lastIndexOf('/') + 1);
    $('#name').attr('value', storyTitle);
}

/* 设置预览的链接。*/
function setPreview()
{
    if(!$('#story').val())
    {
        $('#preview').addClass('hidden');
    }
    else
    {
        storyLink = createLink('story', 'view', "storyID=" + $('#story').val());
        $('#preview').removeClass('hidden');
        $('#preview').attr('href', storyLink);
    }
}
$(document).ready(function()
{
    setPreview();
});
</script>
<div class='yui-d0'>
  <form method='post' enctype='multipart/form-data' target='hiddenwin'>
    <table align='center' class='table-1 a-left'> 
      <caption><?php echo $lang->task->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->task->project;?></th>
        <td><?php echo $project->name;?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->story;?></th>
        <td>
          <?php echo html::select('story', $stories, $storyID, 'class=select-1 onchange=setPreview();');?>
          <a href='' id='preview' class='iframe' target='_blank'><?php echo $lang->preview;?></a>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->name;?></th>
        <td>
          <?php
          echo html::input('name', '', "class='text-1'");
          echo html::commonButton($lang->task->copyStoryTitle, 'onclick=copyStoryTitle()');?>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->desc;?></th>
        <td><?php echo html::textarea('desc', '', "rows='5' class='area-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->files;?></th>
        <td class='a-left'><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->task->owner;?></th>
        <td><?php echo html::select('owner', $members, '', 'class=select-3');?> 
      </tr> 
      <tr>
        <th class='rowhead'><?php echo $lang->task->estimate;?></th>
        <td><?php echo html::input('estimate', '', "class='text-3'");?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->task->deadline;?></th>
        <td><?php echo html::input('deadline', '', "class='text-3 date'");?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->task->type;?></th>
        <td><?php echo html::select('type', $lang->task->typeList, '', 'class=select-3');?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->task->status;?></th>
        <td><?php echo html::select('status', $lang->task->statusList, 'wait', 'class=select-3');?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->task->pri;?></th>
        <td><?php echo html::select('pri', $lang->task->priList, '', 'class=select-3');?> 
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->task->afterSubmit;?></th>
        <td><?php echo html::radio('after', $lang->task->afterChoices, 'continueAdding');?></td> 
      </tr>
      <tr>
        <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
