<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 2343 2011-11-21 05:24:56Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->project->featureBar['all'] as $key => $label):?>
    <?php echo html::a(inlink("project", "status=$key&productID=$productID"), "<span class='text'>{$label}</span>" . ($status == $key ? " <span class='label label-light label-badge'>" . count($projectStats) . "</span>" : ''), '', "class='btn btn-link" . ($status == $key ? ' btn-active-text' : '') . "' id='{$key}Tab'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('PRJMine', array('1' => $lang->program->mine), '', $this->cookie->PRJMine ? 'checked=checked' : '');?>
    <div class="tip"><icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content=<?php echo $lang->product->projectInfo;?>></icon></div>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($projectStats)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->program->noPRJ;?></span>
    </p>
  </div>
  <?php else:?>
  <form class='main-table table-project'>
    <table class="table table-fixed">
      <thead>
        <tr>
          <th class='c-id w-50px'><?php echo $lang->idAB;?></th>
          <th><?php echo $lang->program->PRJName;?></th>
          <th class='w-150px'><?php echo $lang->program->common;?></th>
          <th class='w-80px'><?php echo $lang->program->PM;?></th>
          <th class='w-100px'><?php echo $lang->program->begin;?></th>
          <th class='w-100px'><?php echo $lang->program->end;?></th>
          <th class='w-100px'><?php echo $lang->program->PRJStatus;?></th>
          <th class='w-100px'><?php echo $lang->program->PRJBudget;?></th>
          <th class='w-80px text-center'><?php echo $lang->program->PRJEstimate;?></th>
          <th class='w-80px text-center'><?php echo $lang->program->PRJConsume;?></th>
          <th class='w-60px'><?php echo $lang->program->PRJProgress;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $id = 0;?>
        <?php foreach($projectStats as $project):?>
        <tr>
          <td><?php printf('%03d', $project->id);?></td>
          <td class='text-left'><?php echo html::a($this->createLink('project', 'task', 'project=' . $project->id, '', false, $project->id), $project->name, '_parent');?></td>
          <td><?php echo isset($project->programName) ? $project->programName : '';?></td>
          <td>
            <?php $userID = isset($PMList[$project->PM]) ? $PMList[$project->PM]->id : ''?>
            <?php if(!empty($project->PM)) echo html::a($this->createLink('user', 'profile', "userID=$userID", '', true), zget($users, $project->PM), '', "data-toggle='modal' data-type='iframe' data-width='600'");?>
          </td>
          <td><?php echo $project->begin;?></td>
          <td><?php echo $project->end;?></td>
          <?php $status = $this->processStatus('project', $project);?>
          <td class='c-status' title='<?php echo $status;?>'>
            <span class="status-project status-<?php echo $project->status?>"><?php echo $status;?></span>
          </td>
          <td><?php echo $project->budget != 0 ? $project->budget . zget($this->lang->program->unitList, $project->budgetUnit) : $this->lang->program->future;?></td>
          <td class="text-center"><?php echo $project->hours->totalEstimate;?></td>
          <td class="text-center"><?php echo $project->hours->totalConsumed;?></td>
          <td>
            <div class='progress-pie' data-doughnut-size='80' data-color='#00da88' data-value='<?php echo $project->hours->progress;?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
              <div class='progress-info'><?php echo $project->hours->progress;?>%</div>
            </div>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </form>
  <?php endif;?>
</div>
<script>$('[data-toggle="popover"]').popover();</script>
<?php include '../../common/view/footer.html.php';?>
