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
    <?php foreach($lang->execution->featureBar['all'] as $key => $label):?>
    <?php echo html::a(inlink("project", "status=$key&productID=$productID"), "<span class='text'>{$label}</span>" . ($status == $key ? " <span class='label label-light label-badge'>" . count($projectStats) . "</span>" : ''), '', "class='btn btn-link" . ($status == $key ? ' btn-active-text' : '') . "' id='{$key}Tab'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('involved', array('1' => $lang->project->mine), '', $this->cookie->involved ? 'checked=checked' : '');?>
    <div class="tip"><icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content=<?php echo $lang->product->projectInfo;?>></icon></div>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($projectStats)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->project->empty;?></span>
    </p>
  </div>
  <?php else:?>
  <form class='main-table table-project'>
    <table class="table table-fixed">
      <thead>
        <tr>
          <th class='c-id w-50px'><?php echo $lang->idAB;?></th>
          <th><?php echo $lang->project->name;?></th>
          <?php if($config->systemMode == 'new'):?>
          <th class='w-150px'><?php echo $lang->program->common;?></th>
          <?php endif;?>
          <th class='w-80px text-left'><?php echo $lang->project->PM;?></th>
          <th class='w-80px'><?php echo $lang->project->begin;?></th>
          <th class='w-80px'><?php echo $lang->project->end;?></th>
          <th class='w-70px'><?php echo $lang->project->status;?></th>
          <th class="w-80px text-center"><?php echo $lang->project->budget;?></th>
          <th class="w-60px text-center"><?php echo $lang->project->estimate;?></th>
          <th class="w-60px text-center"><?php echo $lang->project->consume;?></th>
          <th class='w-50px'><?php echo $lang->project->progress;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $id = 0;?>
        <?php foreach($projectStats as $project):?>
        <tr>
          <td><?php printf('%03d', $project->id);?></td>
          <td class='text-left'>
            <?php
            if($config->systemMode == 'new')
            {
                echo html::a($this->createLink('project', 'index', 'project=' . $project->id, '', false, $project->id), $project->name, '_parent');
            }
            else
            {
                echo html::a($this->createLink('execution', 'task', 'project=' . $project->id, '', false, $project->id), $project->name, '_parent');
            }
            ?>
          </td>
          <?php if($config->systemMode == 'new'):?>
          <td title='<?php echo $project->programName;?>' class='text-ellipsis'><?php echo $project->programName;?></td>
          <?php endif;?>
          <td class='padding-right'>
            <?php $userID = isset($PMList[$project->PM]) ? $PMList[$project->PM]->id : ''?>
            <?php if(!empty($project->PM)) echo html::a($this->createLink('user', 'profile', "userID=$userID", '', true), zget($users, $project->PM), '', "data-toggle='modal' data-type='iframe' data-width='800'");?>
          </td>
          <td class='padding-right text-left'><?php echo $project->begin;?></td>
          <td class='padding-right text-left'><?php echo $project->end;?></td>
          <?php $status = $this->processStatus('project', $project);?>
          <td class='c-status' title='<?php echo $status;?>'>
            <span class="status-project status-<?php echo $project->status?>"><?php echo $status;?></span>
          </td>
          <?php $projectBudget = in_array($this->app->getClientLang(), ['zh-cn','zh-tw']) ? round((float)$project->budget / 10000, 2) . $this->lang->project->tenThousand : round((float)$project->budget, 2);?>
          <?php $budgetTitle   = $project->budget != 0 ? zget($this->lang->project->currencySymbol, $project->budgetUnit) . ' ' . $projectBudget : $this->lang->project->future;?>
          <?php $textStyle = $project->budget != 0 ? 'text-right' : 'text-center';?>
          <td title='<?php echo $budgetTitle;?>' class="text-ellipsis <?php echo $textStyle;?>"><?php echo $budgetTitle;?></td>
          <td class="text-right padding-right" title="<?php echo $project->hours->totalEstimate . ' ' . $lang->execution->workHour;?>"><?php echo $project->hours->totalEstimate . $lang->execution->workHourUnit;?></td>
          <td class="text-right padding-right" title="<?php echo $project->hours->totalConsumed . ' ' . $lang->execution->workHour;?>"><?php echo $project->hours->totalConsumed . $lang->execution->workHourUnit;?></td>
          <td class='padding-right text-center'>
            <div class='progress-pie' data-doughnut-size='90' data-color='#00da88' data-value='<?php echo $project->hours->progress;?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
              <div class='progress-info'><?php echo $project->hours->progress;?></div>
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
