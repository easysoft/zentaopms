<?php
/**
 * The complete file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('oldConsumed', $task->consumed);?>
<?php js::set('team', $task->team);?>
<?php js::set('members', $members);?>
<?php js::set('teamMemberError', $lang->task->error->teamMember);?>
<?php js::set('totalLeftError', sprintf($this->lang->task->error->leftEmptyAB, $this->lang->task->statusList[$task->status]));?>
<div id='mainContent' class='main-content'>
  <div class='center-block' id='taskTeamEditor'>
    <?php if(empty($task->members) and !isset($task->members[$app->user->account])):?>
    <div class="alert with-icon">
      <i class="icon-exclamation-sign"></i>
      <div class="content">
        <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $lang->task->teamMember . '</strong>', $lang->task->transfer);?></p>
      </div>
    </div>
    <?php else:?>
    <div class='main-header'>
      <h2>
        <?php $name = $lang->task->team . ' > ' . $task->name;?>
        <?php echo isonlybody() ? ("<span title='$name'>" . $name . '</span>') : html::a($this->createLink('task', 'view', 'task=' . $task->id), $name);?>
        <?php if(!isonlybody()):?>
        <small> <?php echo $lang->arrow . (empty($task->team) ? $lang->task->assign : $lang->task->transfer);?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' target='hiddenwin' action='<?php echo inlink('editTeam', "executionID=$task->execution&taskID=$task->id")?>' id='teamForm'>
      <table class='table table-form'>
        <tbody class="sortable">
          <tr class='hidden'>
            <th><?php echo $lang->task->estimate;?></th>
            <td>
              <?php $disabled = (!empty($task->team) or $task->parent < 0) ? "disabled='disabled'" : '';?>
              <?php echo html::input('estimate', $task->estimate, "class='form-control' {$disabled}");?>
            </td>
          </tr>
          <?php echo html::hidden('status', $task->status);?>
          <tr class='hidden'>
            <th><?php echo $lang->task->left;?></th>
            <td>
              <?php $disabled = (!empty($task->team)  or $task->parent < 0) ? "disabled='disabled'" : '';?>
              <?php echo html::input('left', $task->left, "class='form-control' {$disabled}");?>
            </td>
          </tr>
          <?php include dirname(__FILE__) . DS . 'taskteam.html.php';?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='3' class='text-center form-actions'><?php echo html::submitButton();?></td>
          </tr>
        </tfoot>
      </table>
    </form>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
