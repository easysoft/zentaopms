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
<?php include '../../common/view/datepicker.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['project']);?> <strong><?php echo $project->id;?></strong></span>
      <strong><?php echo html::a($this->createLink('project', 'view', 'project=' . $project->id), $project->name, '_blank');?></strong>
      <small class='text-muted'> <?php echo $lang->project->putoff;?> <?php echo html::icon($lang->icons['putoff']);?></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->project->dateRange;?></th>
        <td class='w-p50'>
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
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->project->days;?></th>
        <td>
          <div class='input-group'>
          <?php echo html::input('days', $project->days, "class='form-control' autocomplete='off'");?>
            <span class='input-group-addon'><?php echo $lang->project->day;?></span>
          </div>
        </td>
      </tr> 
      <tr>
        <th><?php echo $lang->comment;?></th>
        <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
      </tr>
      <tr>
        <th></th><td><?php echo html::submitButton() . html::linkButton($lang->goback, $this->session->taskList); ?></td>
      </tr>
    </table>
  </form>
  <div class='main'><?php include '../../common/view/action.html.php';?></div>
</div>
<?php js::set('weekend', $config->project->weekend);?>
<?php include '../../common/view/footer.html.php';?>
