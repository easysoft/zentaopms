<?php
/**
 * The suspend file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     project 
 * @version     $Id: suspend.html.php 935 2013-01-16 07:49:24Z wwccss@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<div class='container'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['project']);?> <strong><?php echo $project->id;?></strong></span>
      <strong><?php echo html::a($this->createLink('project', 'view', 'project=' . $project->id), $project->name, '_blank');?></strong>
      <small class='text-success'> <?php echo $lang->project->activate;?> <?php echo html::icon($lang->icons['activate']);?></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px text-right'><?php echo $lang->project->beginAndEnd;?></th>
        <td class='w-250px'>
          <div id='sourceTimeBox'>
            <span style='display:inline-block; padding:5px 10px; width:85px'><?php echo $project->begin?></span>
            <span style='padding:5px 10px'> ~ </span>
            <span style='display:inline-block; padding:5px 10px; width:85px'><?php echo $project->end;?></span>
          </div>
          <div id='readjustTimeBox' class='hide'>
            <span style='display:inline-block; width:85px'><?php echo html::input('begin', $newBegin, "class='form-control form-date'")?></span>
            <span style='padding:5px 10px;'> ~ </span>
            <span style='display:inline-block; width:85px'><?php echo html::input('end', $newEnd, "class='form-control form-date'");?></span>
          </div>
        </td>
        </td>
        <td>
          <label class="checkbox-inline"><input name="readjustTime" value="1" id="readjustTime" type="checkbox"> <strong><?php echo $lang->project->readjustTime?></strong></label>
          <span id='readjustTaskBox' class='hide'><label class="checkbox-inline"><input name="readjustTask" value="1" id="readjustTask" type="checkbox"> <strong><?php echo $lang->project->readjustTask?></strong></label></span>
        </td>
      </tr>
      <tr>
        <th class='text-right'><?php echo $lang->comment;?></th>
        <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
      </tr>
      <tr>
        <th></th>
        <td><?php echo html::submitButton() . html::linkButton($lang->goback, $this->session->taskList); ?></td>
      </tr>
    </table>
  </form>
  <div class='main'><?php include '../../common/view/action.html.php';?></div>
</div>
<script>
$(function()
{
    $('#readjustTime').change(function()
    {
        $('#sourceTimeBox').toggle(!$(this).prop('checked'))
        $('#readjustTimeBox').toggle($(this).prop('checked'))
        $('#readjustTaskBox').toggle($(this).prop('checked'))
    })
})
</script>
<?php include '../../common/view/footer.html.php';?>
