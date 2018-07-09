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
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='prefix label-id'><strong><?php echo $project->id;?></strong></span>
      <?php echo isonlybody() ? ("<span title='$project->name'>" . $project->name . '</span>') : html::a($this->createLink('project', 'view', 'project=' . $project->id), $project->name, '_blank');?>
      <?php if(!isonlybody()):?>
      <small><?php echo $lang->arrow . $lang->project->activate;?></small>
      <?php endif;?>
    </h2>
  </div>
  <form class='load-indicator main-form' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='w-70px'><?php echo $lang->project->beginAndEnd;?></th>
        <td class='muted' colspan='2'>
          <div id='sourceTimeBox'><?php echo $project->begin . ' ~ ' . $project->end;?></div>
          <div id='readjustTimeBox' class='hide'>
            <div class='input-group'>
              <?php echo html::input('begin', $newBegin, "class='form-control form-date'")?>
              <span class='input-group-addon'> ~ </span>
              <?php echo html::input('end', $newEnd, "class='form-control form-date'");?>
            </div>
          </div>
        </td>
        <td colspan='3'>
          <div class='clearfix row'>
            <div class='col-md-6 pull-left'>
              <div class="checkbox-primary"><input name="readjustTime" value="1" id="readjustTime" type="checkbox"><label for="readjustTime" class="no-margin"><?php echo $lang->project->readjustTime;?></label></div>
            </div>
            <div class='col-md-6 pull-left'>
              <div id='readjustTaskBox' class='checkbox-primary hide'><input name="readjustTask" value="1" id="readjustTask" type="checkbox"> <label for='readjustTask' class='no-margin'><?php echo $lang->project->readjustTask?></label></div>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->comment;?></th>
        <td colspan='5'><?php echo html::textarea('comment', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
      </tr>
      <tr>
        <td class='text-center form-actions' colspan='6'><?php echo html::submitButton('', '', 'btn btn-wide btn-primary') . html::linkButton($lang->goback, $this->session->taskList, 'self', '', 'btn btn-wide'); ?></td>
      </tr>
    </table>
  </form>
  <hr class='small' />
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
