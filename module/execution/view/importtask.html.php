<?php
/**
 * The importtask view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: importtask.html.php 4669 2013-04-23 02:28:08Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if(isonlybody()):?>
<style>#importTaskForm .table-footer .pager {z-index: 105;}</style>
<?php endif;?>
<?php js::set('isonlybody', isonlybody());?>
<div id='mainMenu' class='clearfix'>
  <div class='pull-left btn-toolbar'>
    <?php echo html::a($this->createLink('execution', 'importTask', "execution=$executionID"), "<span class='text'>{$lang->execution->importTask}</span>", '', "class='btn btn-link btn-active-text'");?>
    <div class='input-control input-group space w-150px'>
      <?php $executions = array(0 => $lang->execution->allExecutions) + $executions;?>
      <span class='input-group-addon'><?php echo $lang->execution->selectExecution;?></span>
      <?php  echo html::select('fromexecution', $executions, $fromExecution, "onchange='reload($executionID, this.value)' class='form-control chosen'");?>
    </div>
  </div>
</div>
<div id='mainContent'>
  <form class='main-table' method='post' target='hiddenwin' id='importTaskForm' data-ride='table'>
    <table class='table table-fixed'>
      <thead>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-name'><?php echo $lang->execution->name ?></th>
          <th class='c-pri' title=<?php echo $lang->execution->pri;?>><?php echo $lang->priAB;?></th>
          <?php if(isonlybody()):?>
          <th class='w-p25'><?php echo $lang->task->name;?></th>
          <?php else:?>
          <th class='w-p30'><?php echo $lang->task->name;?></th>
          <?php endif;?>
          <th class='c-user'><?php echo $lang->task->assignedTo;?></th>
          <th class='c-hour'><?php echo $lang->task->leftAB;?></th>
          <th class='c-date text-center'><?php echo $lang->task->deadlineAB;?></th>
          <th class='c-status'><?php echo $lang->statusAB;?></th>
          <?php if($execution->lifetime != 'ops' and !in_array($execution->attribute, array('request', 'review'))):?>
          <th class='c-story'><?php echo $lang->task->story;?></th>
          <?php endif;?>
        </tr>
      </thead>
      <tbody>
        <?php foreach($tasks2Imported as $task):?>
        <?php $class = $task->assignedTo == $app->user->account ? 'style=color:red' : '';?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='tasks[]' value='<?php echo $task->id;?>' />
              <label></label>
            </div>
            <?php printf('%03d', $task->id);?>
          </td>
          <td title="<?php echo $executions[$task->execution];?>"><?php echo $executions[$task->execution];?></td>
          <td><span class='label-pri label-pri-<?php echo $task->pri;?>' title='<?php echo zget($lang->task->priList, $task->pri, $task->pri);?>'><?php echo $task->pri == '0' ? '' : zget($lang->task->priList, $task->pri, $task->pri);?></span></td>
          <td class='text-left nobr'><?php if(!common::printLink('task', 'view', "task=$task->id", $task->name, '', "class='preview iframe' data-width='90%'", true, true)) echo $task->name;?></td>
          <td <?php echo $class;?>><?php echo $task->assignedToRealName;?></td>
          <td title="<?php echo $task->left . ' ' . $lang->execution->workHour;?>"><?php echo $task->left . ' ' . $lang->execution->workHourUnit;?></td>
          <td class="text-center <?php if(isset($task->delay)) echo 'delayed';?>"><?php if(substr($task->deadline, 0, 4) > 0) echo '<span>' . $task->deadline . '</span>';?></td>
          <td><span class='status-task status-<?php echo $task->status;?>'><?php echo $this->processStatus('task', $task);?></span></td>
          <?php if($execution->lifetime != 'ops' and !in_array($execution->attribute, array('request', 'review'))):?>
          <td class='text-left text-ellipsis' title="<?php echo $task->storyTitle;?>">
            <?php
            if($task->storyID)
            {
                if(common::hasPriv('execution', 'storyView'))
                {
                    echo html::a($this->createLink('execution', 'storyView', "storyid=$task->storyID", '', true), $task->storyTitle, '', "class='preview'");
                }
                else
                {
                    echo $task->storyTitle;
                }
            }
            ?>
          </td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($tasks2Imported or isonlybody()):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar show-always"><?php echo html::submitButton('<i class="icon icon-import icon-sm"></i> ' . $lang->execution->importTask, '', 'btn btn-secondary btn-wide');?></div>
      <div class='btn-toolbar'>
        <?php if(isonlybody()):?>
        <?php echo html::commonButton('<i class="icon icon-sm"></i> ' . $lang->goback, 'onclick="goback()"', 'btn');?>
        <?php else:?>
        <?php echo html::backButton('','','btn');?>
        <?php endif;?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
