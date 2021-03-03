<?php
/**
 * The delay file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     project 
 * @version     $Id: delay.html.php 935 2013-01-16 07:49:24Z wwccss@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='prefix label-id'><strong><?php echo $project->id;?></strong></span>
      <?php echo isonlybody() ? ("<span title='$project->name'>" . $project->name . '</span>') : html::a($this->createLink('project', 'view', 'project=' . $project->id), $project->name, '_blank');?>
      <?php if(!isonlybody()):?>
      <small><?php echo $lang->arrow . $lang->project->putoff;?></small>
      <?php endif;?>
    </h2>
  </div>
  <form class='load-indicator main-form' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th class='w-80px'><?php echo $lang->project->dateRange;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::input('begin', $project->begin, "class='form-control form-date' onchange='computeWorkDays()' placeholder='" . $lang->project->begin . "'");?>
              <span class='input-group-addon'><?php echo $lang->project->to;?></span>
              <?php echo html::input('end', $project->end, "class='form-control form-date' onchange='computeWorkDays()' placeholder='" . $lang->project->end . "'");?>
              <div class='input-group-btn'>
                <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><?php echo $lang->project->byPeriod;?> <span class='caret'></span></button>
                <ul class='dropdown-menu'>
                  <?php foreach ($lang->project->endList as $key => $name):?>
                  <li><a href='javascript:computeEndDate("<?php echo $key;?>")'><?php echo $name;?></a></li>
                  <?php endforeach;?>
                </ul>
              </div>
            </div>
          </td>
          <td class='w-100px'></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->days;?></th>
          <td colspan='2'>
            <div class='input-group'>
            <?php echo html::input('days', $project->days, "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->project->day;?></span>
            </div>
          </td> 
          <td></td>
        </tr> 
        <tr class='hide'>
          <th><?php echo $lang->project->status;?></th>
          <td><?php echo html::hidden('status', $project->status);?></td>
        </tr>
        <?php $this->printExtendFields($project, 'table', 'columns=3');?>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='3'><?php echo html::textarea('comment', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->session->taskList, 'self', '', 'btn btn-wide'); ?></td>
        </tr>
      </tbody>
    </table>
  </form>
  <hr class='small' />
  <div class='main'><?php include '../../common/view/action.html.php';?></div>
</div>
<?php js::set('weekend', $config->project->weekend);?>
<?php include '../../common/view/footer.html.php';?>
