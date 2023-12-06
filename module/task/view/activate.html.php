<?php
/**
 * The activate file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: start.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('oldConsumed', $task->consumed);?>
<?php js::set('currentUser', $app->user->account);?>
<?php js::set('members', $members);?>
<?php js::set('teamMemberError', $lang->task->error->teamMember);?>
<?php js::set('teamLeftEmpty', $lang->task->error->teamLeftEmpty);?>
<?php js::set('totalLeftError', sprintf($this->lang->task->error->leftEmptyAB, $this->lang->task->statusList[$task->status]));?>
<?php js::set('estimateNotEmpty', sprintf($lang->task->error->notempty, $lang->task->estimate))?>
<?php js::set('leftNotEmpty', sprintf($lang->task->error->notempty, $lang->task->left))?>
<?php js::set('teamNotEmpty', sprintf($lang->error->notempty, $lang->task->assignedTo))?>
<?php $isMultiple = !empty($task->team);?>
<?php js::set('isMultiple', $isMultiple);?>
<?php js::set('taskMode', $task->mode);?>
<?php if($isMultiple) js::set('assignedToHtml', html::select('assignedTo', $teamMembers, '', "class='form-control' disabled"));?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $task->id;?></span>
        <?php echo isonlybody() ? ("<span title='$task->name'>" . $task->name . '</span>') : html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name);?>
        <?php if(!isonlybody()):?>
        <small> <?php echo $lang->arrow . $lang->task->activate;?></small>
        <?php endif;?>
      </h2>
    </div>

    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <?php if($isMultiple):?>
        <tr>
          <th class='thWidth'><?php echo $lang->task->mode;?></th>
          <td class='w-p35-f'><?php echo zget($lang->task->modeList, $task->mode) . html::hidden('mode', $task->mode);?></td>
          <td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th class='thWidth'><?php echo $lang->task->assignedTo;?></th>
          <td class='w-p35-f'>
            <div class="input-group<?php if($isMultiple) echo ' required';?>" id="dataPlanGroup">
              <?php echo html::select('assignedTo', $isMultiple ? $teamMembers : $members, $isMultiple ? '' : $task->finishedBy, "class='form-control chosen'");?>
              <?php if($isMultiple):?>
              <span class="input-group-btn team-group hidden"><a class="btn br-0" href="#modalTeam" data-toggle="modal"><?php echo $lang->task->team;?></a></span>
              <?php endif;?>
            </div>
          </td>
          <td>
            <?php if($isMultiple):?>
            <div class="checkbox-primary c-multipleTask affair">
              <input type="checkbox" name="multiple" value="1" id="multiple" /><label for="multiple" class="no-margin"><?php echo $lang->task->manageTeam;?></label>
            </div>
            <?php endif;?>
          </td>
        </tr>
        <?php if($task->parent != '-1'):?>
        <tr>
          <th><?php echo $lang->task->left;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('left', '', "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->task->hour;?></span>
            </div>
          </td>
        </tr>
        <?php endif;?>
        <tr class='hide'>
          <th><?php echo $lang->task->status;?></th>
          <td><?php echo html::hidden('status', 'doing');?></td>
        </tr>
        <?php $this->printExtendFields($task, 'table', 'columns=2');?>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='w-p98'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center'>
           <?php
           echo html::submitButton($lang->task->activate);
           echo html::linkButton($lang->goback, $this->session->taskList);
           ?>
          </td>
        </tr>
      </table>

      <div class='modal fade modal-team' id='modalTeam' data-scroll-inside='false'>
        <div class='modal-dialog'>
          <div class='modal-content with-padding'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'>
                <i class="icon icon-close"></i>
              </button>
              <h4 class='modal-title'><?php echo $lang->task->team;?></h4>
            </div>
            <div class='modal-body'>
              <table class="table table-form" id='taskTeamEditor'>
                <tbody class='sortable'>
                  <?php include __DIR__ . DS . 'taskteam.html.php';?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan='4' class='text-center form-actions'>
                      <div class='multi-append'></div>
                      <?php echo html::a('javascript:void(0)', $lang->confirm, '', "id='confirmButton' class='btn btn-primary btn-wide'");?>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>
<?php js::set('newRowCount', (!empty($task->team) and count($task->team) < 6) ? 6 - count($task->team) : 1);?>
<?php include '../../common/view/footer.html.php';?>
