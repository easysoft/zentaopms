<?php
/**
 * The browsebycard view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     project
 * @version     $Id: browsebycard.html.php 4769 2021-07-23 11:29:21Z $
 * @link        https://www.zentao.net
 */
?>
<div id="mainMenu" class="clearfix table-row">
  <div class="btn-toolBar pull-left">
    <div class="input-control w-150px" id='programBox'><?php echo html::select('programID', $programs, $programID, "onchange=changeProgram(this.value) class='form-control chosen' data-placeholder='{$lang->project->selectProgram}'");?></div>
    <?php foreach($lang->project->featureBar as $key => $label):?>
    <?php $active = $browseType == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($browseType == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('browse', "programID=$programID&browseType=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('involved', array('1' => $lang->project->mine), '', $this->cookie->involved ? 'checked=checked' : '');?>
  </div>
  <div class="btn-toolbar pull-right">
    <div class="btn-group panel-actions">
      <?php echo html::a('#',"<i class='icon-cards-view'></i> &nbsp;", '', "class='btn btn-icon text-primary' title='{$lang->project->bycard}' id='switchButton' data-type='bycard'");?>
      <?php echo html::a('#',"<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon' title='{$lang->project->bylist}' id='switchButton' data-type='bylist'");?>
    </div>
    <?php if(isset($this->config->maxVersion)):?>
    <?php common::printLink('project', 'createGuide', "programID=$programID", '<i class="icon icon-plus"></i> ' . $lang->project->create, '', 'class="btn btn-primary" data-toggle="modal" data-target="#guideDialog"');?>
    <?php elseif($this->config->systemMode == 'new'):?>
    <?php common::printLink('project', 'create', 'mode=scrum', '<i class="icon icon-plus"></i> ' . $lang->project->create, '', 'class="btn btn-primary"');?>
    <?php else:?>
    <?php common::printLink('execution', 'create', '', '<i class="icon icon-plus"></i> ' . $lang->execution->create, '', 'class="btn btn-primary"');?>
    <?php endif;?>
  </div>
</div>
<div id='mainContent'>
  <div class='row cell' id='cards'>
    <?php if(empty($projectStats)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->project->empty;?></span>
        <?php if(isset($this->config->maxVersion)):?>
        <?php common::printLink('project', 'createGuide', "programID=$programID", '<i class="icon icon-plus"></i> ' . $lang->project->create, '', 'class="btn btn-info" data-toggle="modal" data-target="#guideDialog"');?>
        <?php elseif($this->config->systemMode == 'new'):?>
        <?php common::printLink('project', 'create', 'mode=scrum', '<i class="icon icon-plus"></i> ' . $lang->project->create, '', 'class="btn btn-info"');?>
        <?php else:?>
        <?php common::printLink('execution', 'create', '', '<i class="icon icon-plus"></i> ' . $lang->execution->create, '', 'class="btn btn-info"');?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <?php foreach ($projectStats as $projectID => $project):?>
    <div class='col' data-id='<?php echo $projectID?>'>
      <div class='panel'>
        <div class='projectStatus'>
          <?php $status = ($project->status == 'doing' and isset($project->delay)) ? 'delay' : $project->status;?>
          <span class="label label-<?php echo $status;?>"><?php echo $lang->project->statusList[$status];?></span>
        </div>
        <div class='panel-heading'>
          <?php if(isset($config->maxVersion) and $project->model === 'waterfall'):?>
          <span class='project-type-label label label-warning label-outline'><i class='icon icon-waterfall'></i></span>
          <?php elseif(isset($config->maxVersion)):?>
          <span class='project-type-label label label-info label-outline'><i class='icon icon-sprint'></i></span>
          <?php endif;?>
          <strong class='project-name' title='<?php echo $project->name;?>'><?php echo html::a(helper::createLink('project', 'index', "projectID=$projectID"), $project->name);?></strong>
        </div>
        <div class='panel-body'>
          <div class='project-infos'>
            <?php
            $projectBudget = in_array($app->getClientLang(), ['zh-cn','zh-tw']) ? round((float)$project->budget / 10000, 2) . $lang->project->tenThousand : round((float)$project->budget, 2);
            $budgetTitle   = $project->budget != 0 ? zget($lang->project->currencySymbol, $project->budgetUnit) . ' ' . $projectBudget : $lang->project->budget . $lang->project->future;
            $project->end  = $project->end == LONG_TIME ? $this->lang->project->longTime : $project->end;
            $project->date = str_replace('-', '.', $project->begin) . ' - ' . str_replace('-', '.', $project->end);
            ?>
            <span title="<?php echo $budgetTitle;?>" class='label label-outline budget'><?php echo $budgetTitle;?></span>
            <span title="<?php echo $project->date;?>" class="label label-outline <?php echo $status == 'delay' ? 'text-red' : '';?>"><?php echo $project->date;?></span>
          </div>
          <div class='project-detail'>
            <div class='row'>
              <div class='col-xs-2'>
                <div class='progress-pie' data-doughnut-size='90' data-color='#00da88' data-value="<?php echo $project->hours->progress?>" data-width='24' data-height='24' data-back-color='#e8edf3'>
                  <div class='progress-info'><?php echo $project->hours->progress;?></div>
                </div>
              </div>
              <div class='col-xs-4'>
                <span><?php echo $lang->project->leftTasks;?></span>
                <span title="<?php echo $project->leftTasks;?>"><?php echo $project->leftTasks;?></span>
              </div>
              <div class='col-xs-4'>
                <span><?php echo $lang->project->leftHours;?></span>
                <span title="<?php echo empty($project->hours->totalLeft) ? '—' : $project->hours->totalLeft . $lang->execution->workHour;?>"><?php echo empty($project->hours->totalLeft) ? '—' : $project->hours->totalLeft . $lang->execution->workHourUnit;?></span>
              </div>
            </div>
          </div>
          <div class='project-footer table-row'>
            <div class='project-members table-col'>
            <?php if(!empty($project->teamMembers)):?>
              <a href='<?php echo helper::createLink('project', 'manageMembers', "projectID=$projectID");?>'>
              <?php foreach($project->teamMembers as $key => $member):?>
              <?php if($key > 4) continue;?>
                <div class='avatar bg-secondary avatar-circle'>
                  <?php echo !empty(zget($usersAvatar, $member, '')) ? html::image(zget($usersAvatar, $member)) : strtoupper($member[0]);?>
                </div>
              <?php endforeach;?>
              </a>
              <?php if($project->teamCount > 5):?>
                <div class='moreMembers'><?php echo html::a(helper::createLink('project', 'manageMembers', "projectID=$projectID"), '+' . ($project->teamCount - 5));?></div>
              <?php endif;?>
            <?php endif;?>
            </div>
            <div class='project-actions table-col'>
              <div class='menu-actions'>
                <?php
                if($project->status == 'wait' || $project->status == 'suspended') common::printIcon('project', 'start', "projectID=$project->id", $project, 'list', 'play', '', 'iframe btn-action', true);
                if($project->status == 'doing') common::printIcon('project', 'close', "projectID=$project->id", $project, 'list', 'off', '', 'iframe btn-action', true);
                if($project->status == 'closed') common::printIcon('project', 'activate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe btn-action', true);
                ?>
                <?php $canActions = (common::hasPriv('project','suspend') || (common::hasPriv('project','close') && $project->status != 'doing') || (common::hasPriv('project','activate') && $project->status != 'closed'));?>
                <?php if($canActions):?>
                <?php echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");?>
                <ul class='dropdown-menu pull-right'>
                  <?php
                  common::printIcon('project', 'suspend', "projectID=$project->id", $project, 'list', 'pause', '', 'iframe btn-action', true);
                  if($project->status != 'doing') common::printIcon('project', 'close', "projectID=$project->id", $project, 'list', 'off', '', 'iframe btn-action', true);
                  if($project->status != 'closed') common::printIcon('project', 'activate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe btn-action', true);
                  ?>
                </ul>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
    <div class='col-xs-12' id='cardsFooter'>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </div>
</div>
<script>
$('.progress-pie:visible').progressPie();
</script>
