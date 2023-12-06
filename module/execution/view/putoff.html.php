<?php
/**
 * The delay file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     execution 
 * @version     $Id: delay.html.php 935 2013-01-16 07:49:24Z wwccss@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='prefix label-id'><strong><?php echo $execution->id;?></strong></span>
      <?php echo isonlybody() ? ("<span title='$execution->name'>" . $execution->name . '</span>') : html::a($this->createLink('execution', 'view', 'execution=' . $execution->id), $execution->name, '_blank');?>
      <?php if(!isonlybody()):?>
      <small><?php echo $lang->arrow . $lang->execution->putoff;?></small>
      <?php endif;?>
    </h2>
  </div>
  <form class='load-indicator main-form' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th class='c-date'><?php echo $lang->execution->dateRange;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::input('begin', $execution->begin, "class='form-control form-date' onchange='computeWorkDays()' placeholder='" . $lang->execution->begin . "'");?>
              <span class='input-group-addon'><?php echo $lang->execution->to;?></span>
              <?php echo html::input('end', $execution->end, "class='form-control form-date' onchange='computeWorkDays()' placeholder='" . $lang->execution->end . "'");?>
              <div class='input-group-btn'>
                <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><?php echo $lang->execution->byPeriod;?> <span class='caret'></span></button>
                <ul class='dropdown-menu'>
                  <?php foreach ($lang->execution->endList as $key => $name):?>
                  <li><a href='javascript:computeEndDate("<?php echo $key;?>")'><?php echo $name;?></a></li>
                  <?php endforeach;?>
                </ul>
              </div>
            </div>
          </td>
          <td class='c-date'></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->days;?></th>
          <td colspan='2'>
            <div class='input-group'>
            <?php echo html::input('days', $execution->days, "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->execution->day;?></span>
            </div>
          </td> 
          <td></td>
        </tr> 
        <tr class='hide'>
          <th><?php echo $lang->execution->status;?></th>
          <td><?php echo html::hidden('status', $execution->status);?></td>
        </tr>
        <?php $this->printExtendFields($execution, 'table', 'columns=3');?>
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
<?php js::set('weekend', $config->execution->weekend);?>
<?php include '../../common/view/footer.html.php';?>
