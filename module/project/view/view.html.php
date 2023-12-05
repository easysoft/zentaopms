<?php
/**
 * The view method view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: view.html.php 4594 2013-03-13 06:16:02Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php if(!common::checkNotCN()):?>
<style> table.data-stats > tbody > tr.statsTr > td:first-child {width: 60px;}</style>
<?php endif;?>
<div id='mainContent' class="main-row">
  <div class="col-8 main-col">
    <div class="row">
      <div class="col-sm-6">
        <div class="panel block-dynamic" style="height: 280px">
          <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->execution->latestDynamic;?></div>
            <?php if($project->model != 'kanban' and common::hasPriv('project', 'dynamic')):?>
            <nav class="panel-actions nav nav-default">
              <li><?php common::printLink('project', 'dynamic', "projectID=$project->id&type=all", strtoupper($lang->more), '', "title=$lang->more");?></li>
            </nav>
            <?php endif;?>
          </div>
          <div class="panel-body scrollbar-hover">
            <ul class="timeline timeline-tag-left no-margin">
              <?php foreach($dynamics as $action):?>
              <li <?php if($action->major) echo "class='active'";?>>
                <div>
                  <span class="timeline-tag"><?php echo $action->date;?></span>
                  <span class="timeline-text"><?php echo zget($users, $action->actor) . ' ' . "<span class='label-action'>{$action->actionLabel}</span>" . $action->objectLabel . ' ' . html::a($action->objectLink, $action->objectName, '', "title='$action->objectName'");?></span>
                </div>
              </li>
              <?php endforeach;?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="panel block-team" style="height: 280px">
          <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->execution->relatedMember;?></div>
            <?php if(common::hasPriv('project', 'team')):?>
            <nav class="panel-actions nav nav-default">
              <li><?php common::printLink('project', 'team', "projectID=$project->id", strtoupper($lang->more), '', "title=$lang->more");?></li>
            </nav>
            <?php endif;?>
          </div>
          <div class="panel-body">
            <div class="row row-grid">
              <?php $i = 9; $j = 0;?>
              <?php if($project->PM):?>
              <?php $i--;?>
              <?php unset($teamMembers[$project->PM]);?>
              <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $project->PM);?> <span class="text-muted">（<?php echo $lang->project->PM;?>）</span></div>
              <?php endif;?>
              <?php if($project->PO):?>
              <?php $i--;?>
              <?php unset($teamMembers[$project->PO]);?>
              <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $project->PO);?> <span class="text-muted">（<?php echo $lang->project->PO;?>）</span></div>
              <?php endif;?>
              <?php if($project->QD):?>
              <?php $i--;?>
              <?php unset($teamMembers[$project->QD]);?>
              <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $project->QD);?> <span class="text-muted">（<?php echo $lang->project->QD;?>）</span></div>
              <?php endif;?>
              <?php if($project->RD):?>
              <?php $i--;?>
              <?php unset($teamMembers[$project->RD]);?>
              <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $project->RD);?> <span class="text-muted">（<?php echo $lang->project->RD;?>）</span></div>
              <?php endif;?>

              <?php foreach($teamMembers as $teamMember):?>
              <?php if($j > $i) break;?>
              <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $teamMember->account);?></div>
              <?php $j++;?>
              <?php endforeach;?>
              <div class="col-xs-6">
                <?php common::printLink('project', 'manageMembers', "projectID=$project->id", "<i class='icon icon-plus hl-primary text-primary'></i> &nbsp;" . $lang->project->manageMembers, '', "class='text-muted'");?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php if($isExtended):?>
      <div class="col-sm-12">
        <div class='panel'>
          <?php $this->printExtendFields($project, 'div', "position=left&inForm=0");?>
        </div>
      </div>
      <?php endif;?>
      <div class="col-sm-12">
        <?php $blockHistory = true;?>
        <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=project&objectID=$project->id");?>
        <?php include '../../common/view/action.html.php';?>
      </div>
    </div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php $browseLink = $this->session->projectList ? $this->session->projectList : inlink('browse');?>
        <?php common::printBack($browseLink);?>
        <?php echo $this->project->buildOperateMenu($project, 'view');?>
      </div>
    </div>
  </div>
  <div class="col-4 side-col">
    <div class="row">
      <div class="col-sm-12">
        <div class="cell">
          <div class="detail">
            <?php $hiddenCode = (!isset($config->setCode) or $config->setCode == 0) ? 'hidden' : '';?>
            <h2 class="detail-title"><span class="label-id"><?php echo $project->id;?></span> <span class="label label-light label-outline <?php echo $hiddenCode;?>"><?php echo $project->code;?></span> <?php echo $project->name;?></h2>
            <div class="detail-content article-content">
              <div><span class="text-limit hidden" data-limit-size="40"><?php echo $project->desc;?></span><a class="text-primary text-limit-toggle small" data-text-expand="<?php echo $lang->expand;?>"  data-text-collapse="<?php echo $lang->collapse;?>"></a></div>
              <p>
                <?php if($config->vision == 'rnd'):?>
                <span class="label label-primary label-outline"><?php echo zget($lang->project->projectTypeList, $project->hasProduct);?></span>
                <?php endif; ?>
                <?php if($project->deleted):?>
                <span class='label label-danger label-outline'><?php echo $lang->project->deleted;?></span>
                <?php endif; ?>
                <span class="label label-primary label-outline"><?php echo zget($lang->execution->lifeTimeList, $project->lifetime, '');?></span>
                <?php if(isset($project->delay)):?>
                <span class="label label-danger label-outline"><?php echo $lang->project->delayed;?></span>
                <?php else:?>
                <span class="label status-<?php echo $project->status;?> label-outline"><?php echo $this->processStatus('project', $project);?></span>
                <?php endif;?>
              </p>
            </div>
          </div>
          <?php if(empty($globalDisableProgram)):?>
          <div class="detail">
            <div class="detail-title">
              <strong><?php echo $lang->project->parent;?></strong>
            </div>
            <div class="detail-content">
              <div class="row row-grid">
                <div class="col-xs-12">
                <?php if($project->grade > 1):?>
                  <i class='icon icon-program text-muted'></i>
                  <?php
                  $names = '';
                  foreach($programList as $id => $name)
                  {
                      $names .= common::hasPriv('program', 'product') ? html::a($this->createLink('program', 'product', "programID=$id"), $name) . '/ ' : $name . '/ ';
                  }
                  echo rtrim($names, '/ ');
                  ?>
                <?php endif;?>
                </div>
              </div>
            </div>
          </div>
          <?php endif;?>
          <?php if(!empty($project->hasProduct)):?>
          <div class="detail">
            <div class="detail-title">
              <strong><?php echo $lang->project->manageProducts;?></strong>
              <?php common::printLink('project', 'manageproducts', "projectID=$project->id", strtoupper($lang->more), '', "class='btn btn-link pull-right muted'");?>
            </div>
            <div class="detail-content">
              <div class="row row-grid">
                <?php foreach($products as $productID => $product):?>
                <?php foreach($product->branches as $branchID):?>
                <?php $branchName = isset($branchGroups[$productID][$branchID]) ? '/' . $branchGroups[$productID][$branchID] : '';?>
                <div class="col-xs-6">
                  <?php echo html::a($this->createLink('product', 'browse', "productID=$productID&branch=$branchID"), "<i class='icon icon-product text-muted'></i> " . $product->name . $branchName);?>
                </div>
                <?php endforeach;?>
                <?php endforeach;?>
              </div>
            </div>
          </div>
          <div class="detail">
            <div class="detail-title"><strong><?php echo $lang->execution->linkPlan;?></strong></div>
            <div class="detail-content">
              <div class="row row-grid">
                <?php foreach($products as $productID => $product):?>
                <?php foreach($product->plans as $planIDList):?>
                <?php $planIDList = explode(',', $planIDList);?>
                <?php foreach($planIDList as $planID):?>
                <?php if(isset($planGroup[$productID][$planID])):?>
                <div class="col-xs-12"><?php echo html::a($this->createLink('productplan', 'view', "planID={$planID}"), "<i class='icon icon-calendar text-muted'></i> " . $product->name . '/' . $planGroup[$productID][$planID]);?></div>
                <?php endif;?>
                <?php endforeach;?>
                <?php endforeach;?>
                <?php endforeach;?>
              </div>
            </div>
          </div>
          <?php endif;?>
          <div class='detail'>
            <div class='detail-title'><strong><?php echo $lang->execution->lblStats;?></strong></div>
            <div class="detail-content">
              <table class='table table-data data-stats'>
                <tbody>
                  <tr class='statsTr'><td></td><td></td><td></td><td></td></tr>
                  <tr>
                    <td colspan="4">
                      <?php $progress = $project->model == 'waterfall' ? $this->project->getWaterfallProgress($project->id) : (($workhour->totalConsumed + $workhour->totalLeft) ? floor($workhour->totalConsumed / ($workhour->totalConsumed + $workhour->totalLeft) * 1000) / 1000 * 100 : 0);?>
                      <?php echo $lang->project->progress;?> <?php echo $progress . $lang->percent;?> &nbsp;
                      <div class="progress inline-block">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress . $lang->percent;?>"></div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->project->begin;?></th>
                    <td><?php echo $project->begin;?></td>
                    <th><?php echo $lang->project->realBeganAB;?></th>
                    <td><?php echo $project->realBegan == '0000-00-00' ? '' : $project->realBegan;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->project->end;?></th>
                    <td><?php echo $project->end = $project->end == LONG_TIME ? $this->lang->project->longTime : $project->end;;?></td>
                    <th><?php echo $lang->project->realEndAB;?></th>
                    <td><?php echo $project->realEnd == '0000-00-00' ? '' : $project->realEnd;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->execution->totalEstimate;?></th>
                    <td><?php echo (float)$workhour->totalEstimate . $lang->execution->workHour;?></td>
                    <th><?php echo $lang->execution->totalDays;?></th>
                    <td><?php echo (float)$project->days . $lang->execution->day;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->execution->totalConsumed;?></th>
                    <td><?php echo (float)$workhour->totalConsumed . $lang->execution->workHour;?></td>
                    <th><?php echo $lang->execution->totalHours;?></th>
                    <td><?php echo (float)$workhour->totalHours . $lang->execution->workHour;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->execution->totalLeft;?></th>
                    <td><?php echo (float)$workhour->totalLeft . $lang->execution->workHour;?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="detail">
            <div class="detail-title"><strong><?php echo $lang->execution->basicInfo;?></strong></div>
            <div class="detail-content">
              <table class="table table-data data-basic">
                <tbody>
                  <?php if(empty($project->hasProduct) and !empty($config->URAndSR) and $project->model !== 'kanban' and isset($lang->project->menu->storyGroup)):?>
                  <tr>
                    <th><?php echo $lang->story->common;?></th>
                    <td title="<?php echo $statData->storyCount;?>"><?php echo $statData->storyCount;?></td>
                    <th><?php echo $lang->requirement->common;?></th>
                    <td title="<?php echo $statData->requirementCount;?>"><?php echo $statData->requirementCount;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->task->common;?></th>
                    <td title="<?php echo $statData->taskCount;?>"><?php echo $statData->taskCount;?></td>
                    <th><?php echo $lang->bug->common;?></th>
                    <td title="<?php echo $statData->bugCount;?>"><?php echo $statData->bugCount;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->project->budget;?></th>
                    <td title="<?php echo $project->budget;?>"><?php echo $project->budget;?></td>
                  </tr>
                  <?php else:?>
                  <tr>
                    <th><?php echo $lang->story->common;?></th>
                    <td title="<?php echo $statData->storyCount;?>"><?php echo $statData->storyCount;?></td>
                    <th><?php echo $lang->task->common;?></th>
                    <td title="<?php echo $statData->taskCount;?>"><?php echo $statData->taskCount;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->bug->common;?></th>
                    <td title="<?php echo $statData->bugCount;?>"><?php echo $statData->bugCount;?></td>
                    <th><?php echo $lang->project->budget;?></th>
                    <td title="<?php echo $project->budget;?>"><?php echo $project->budget;?></td>
                  </tr>
                  <?php endif;?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="detail">
            <div class="detail-title"><strong><?php echo $lang->project->acl;?></strong></div>
            <div class="detail-content">
              <?php $aclList = $project->parent ? $lang->project->subAclList : $lang->project->aclList;?>
              <p><?php echo $aclList[$project->acl];?></p>
            </div>
          </div>
        </div>
        <?php $this->printExtendFields($project, 'div', "position=right&inForm=0&inCell=1");?>
      </div>
    </div>
  </div>
</div>

<div id="mainActions" class='main-actions'>
  <nav class="container"></nav>
<?php include '../../common/view/footer.html.php';?>
