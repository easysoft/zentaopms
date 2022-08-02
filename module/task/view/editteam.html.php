<?php
/**
 * The complete file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
<?php js::set('newRowCount', count($task->team) < 6 ? 6 - count($task->team) : 1);?>
<?php js::set('teamMemberError', $lang->task->error->teamMember);?>
<?php js::set('totalLeftError', sprintf($this->lang->task->error->leftEmptyAB, $this->lang->task->statusList[$task->status]));?>
<div id='mainContent' class='main-content'>
  <div class='center-block' id='taskTeamEditor'>
    <?php if(empty($task->team) and !isset($task->team[$app->user->account])):?>
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
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tbody class="sortable">
          <tr class='hidden'>
            <th><?php echo $lang->task->estimate;?></th>
            <td>
              <?php $disabled = (!empty($task->team) or $task->parent < 0) ? "disabled='disabled'" : '';?>
              <?php echo html::input('estimate', $task->estimate, "class='form-control' {$disabled}");?>
            </td>
          </tr>
          <tr class='hidden'>
            <th><?php echo $lang->task->left;?></th>
            <td>
              <?php $disabled = (!empty($task->team)  or $task->parent < 0) ? "disabled='disabled'" : '';?>
              <?php echo html::input('left', $task->left, "class='form-control' {$disabled}");?>
            </td>
          </tr>
          <?php foreach($task->team as $member):?>
          <tr>
            <td class='w-250px'><?php echo html::select("team[]", $members, $member->account, "class='form-control chosen'")?></td>
            <td>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->task->estimate?></span>
                <?php echo html::input("teamEstimate[]", (float)$member->estimate, "class='form-control text-center' placeholder='{$lang->task->hour}'")?>
                <span class='input-group-addon fix-border'><?php echo $lang->task->consumed?></span>
                <?php echo html::input("teamConsumed[]", (float)$member->consumed, "class='form-control text-center' readonly placeholder='{$lang->task->hour}'")?>
                <span class='input-group-addon fix-border'><?php echo $lang->task->left?></span>
                <?php echo html::input("teamLeft[]", (float)$member->left, "class='form-control text-center' placeholder='{$lang->task->hour}'")?>
              </div>
            </td>
            <td class='w-130px sort-handler'>
              <button type="button" class="btn btn-link btn-sm btn-icon btn-add"><i class="icon icon-plus"></i></button>
              <button type='button' class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
              <button type="button" class="btn btn-link btn-sm btn-icon btn-delete"><i class="icon icon-close"></i></button>
            </td>
          </tr>
          <?php endforeach;?>
          <tr class='template'>
            <td class='w-250px'><?php echo html::select("team[]", $members, '', "class='form-control chosen'")?></td>
            <td>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->task->estimate?></span>
                <?php echo html::input("teamEstimate[]", '', "class='form-control text-center' placeholder='{$lang->task->hour}'")?>
                <span class='input-group-addon fix-border'><?php echo $lang->task->consumed?></span>
                <?php echo html::input("teamConsumed[]", '', "class='form-control text-center' placeholder='{$lang->task->hour}'")?>
                <span class='input-group-addon fix-border'><?php echo $lang->task->left?></span>
                <?php echo html::input("teamLeft[]", '', "class='form-control text-center' placeholder='{$lang->task->hour}'")?>
              </div>
            </td>
            <td class='w-130px sort-handler'>
              <button type="button" class="btn btn-link btn-sm btn-icon btn-add"><i class="icon icon-plus"></i></button>
              <button type='button' class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
              <button type="button" class="btn btn-link btn-sm btn-icon btn-delete"><i class="icon icon-close"></i></button>
            </td>
          </tr>
        </tbody>
        <tfoot>
        <tr><td colspan='3' class='text-center form-actions'><?php echo html::submitButton();?></td></tr>
        </tfoot>
      </table>
    </form>
    <hr class='small' />
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
