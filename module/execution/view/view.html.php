<?php
/**
 * The view method view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: view.html.php 4594 2013-03-13 06:16:02Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../ai/view/promptmenu.html.php';?>
<?php js::import($this->config->webRoot . 'js/echarts/echarts.common.min.js'); ?>
<?php js::set('type', $type);?>
<?php js::set('chartData', $chartData);?>
<?php js::set('YUnit', $lang->execution->count); ?>
<?php $style = isonlybody() ? 'style="padding-top: 0px;"' : '';?>
  <div id='mainContent' class="main-row" <?php echo $style;?>>
    <div class="col-8 main-col">
      <div class="row">
        <?php if(isset($execution->type) and $execution->type != 'kanban'):?>
        <div class="col-sm-6">
          <div class="panel block-burn" style="height: 280px">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $execution->name . $lang->execution->burn;?>
                <?php if(isset($execution->delay)):?>
                <span class="label label-danger label-outline"><?php echo $lang->execution->delayed;?></span>
                <?php endif;?>
              </div>
              <?php if(common::hasPriv('execution', 'burn')):?>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('execution', 'burn', "executionID=$execution->id", mb_strtoupper($lang->more), '', "title=$lang->more");?></li>
              </nav>
              <?php endif;?>
            </div>
            <div class="panel-body">
              <?php if(common::hasPriv('execution', 'burn')):?>
              <div id="burnWrapper">
                <div id="burnChart">
                  <canvas id="burnCanvas"></canvas>
                </div>
                <div id="burnYUnit">(<?php echo $lang->execution->workHour;?>)</div>
                <div id="burnLegend" class='table-row'>
                  <div class="line-ref table-col"><?php echo $lang->execution->charts->burn->graph->reference;?></div>
                  <div class="line-real table-col"><?php echo $lang->execution->charts->burn->graph->actuality;?></div>
                  <?php if(isset($execution->delay)):?>
                  <div class="line-delay table-col"><?php echo $lang->execution->charts->burn->graph->delay;?></div>
                  <?php endif;?>
                </div>
              </div>
              <?php endif;?>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel block-dynamic" style="height: 280px">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $lang->execution->latestDynamic;?></div>
              <?php if(common::hasPriv('execution', 'dynamic')):?>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('execution', 'dynamic', "executionID=$execution->id&type=all", mb_strtoupper($lang->more), '', "title=$lang->more");?></li>
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
          <div class="panel block-team" style="height: 240px">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $lang->execution->relatedMember;?></div>
              <?php if(common::hasPriv('execution', 'team')):?>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('execution', 'team', "executionID=$execution->id", mb_strtoupper($lang->more), '', "title=$lang->more");?></li>
              </nav>
              <?php endif;?>
            </div>
            <div class="panel-body">
              <div class="row row-grid">
                <?php $i = 9; $j = 0;?>
                <?php if($execution->PM):?>
                <?php $i--;?>
                <?php unset($teamMembers[$execution->PM]);?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $execution->PM);?> <span class="text-muted">（<?php echo $lang->execution->PM;?>）</span></div>
                <?php endif;?>
                <?php if($execution->PO):?>
                <?php $i--;?>
                <?php unset($teamMembers[$execution->PO]);?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $execution->PO);?> <span class="text-muted">（<?php echo $lang->execution->PO;?>）</span></div>
                <?php endif;?>
                <?php if($execution->QD):?>
                <?php $i--;?>
                <?php unset($teamMembers[$execution->QD]);?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $execution->QD);?> <span class="text-muted">（<?php echo $lang->execution->QD;?>）</span></div>
                <?php endif;?>
                <?php if($execution->RD):?>
                <?php $i--;?>
                <?php unset($teamMembers[$execution->RD]);?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $execution->RD);?> <span class="text-muted">（<?php echo $lang->execution->RD;?>）</span></div>
                <?php endif;?>

                <?php foreach($teamMembers as $teamMember):?>
                <?php if($j > $i) break;?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $teamMember->account);?></div>
                <?php $j++;?>
                <?php endforeach;?>
                <div class="col-xs-6">
                  <?php if($canBeChanged) common::printLink('execution', 'manageMembers', "executionID=$execution->id", "<i class='icon icon-plus hl-primary text-primary'></i> &nbsp;" . $lang->execution->manageMembers, '', "class='text-muted'");?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel block-docs" style="height: 240px">
            <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->execution->doclib;?></div>
              <?php if(common::hasPriv('execution', 'doc')):?>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('execution', 'doc', "executionID=$execution->id", mb_strtoupper($lang->more), '', "title=$lang->more");?></li>
              </nav>
              <?php endif;?>
            </div>
            <div class="panel-body">
              <div class="row row-grid">
                <?php if(common::hasPriv('execution', 'doc')):?>
                <?php $i = 0;?>
                <?php foreach($docLibs as $libID => $docLib):?>
                <?php if($i > 8) break;?>
                <div class="col-xs-6 text-ellipsis">
                  <?php if($libID == 'files'):?>
                    <?php if(isonlybody()):?>
                    <?php echo "<i class='icon icon-folder text-yellow'></i> " . $docLib->name;?>
                    <?php else:?>
                    <?php echo html::a($this->createLink('doc', 'showFiles', "type=execution&objectID=$execution->id"), "<i class='icon icon-folder text-yellow'></i> " . $docLib->name);?>
                    <?php endif;?>
                  <?php else:?>
                    <?php if(isonlybody()):?>
                    <?php echo "<i class='icon icon-folder text-yellow'></i> " . $docLib->name;?>
                    <?php else:?>
                    <?php echo html::a($this->createLink('execution', 'doc', "objectID={$execution->id}&libID=$libID"), "<i class='icon icon-folder text-yellow'></i> " . $docLib->name, '', "data-app='execution' title='$docLib->name'");?>
                    <?php endif;?>
                  <?php endif;?>
                </div>
                <?php $i++;?>
                <?php endforeach;?>
                <div class="col-xs-6">
                  <?php if($canBeChanged) common::printLink('doc', 'createLib', "type=execution&objectID=$execution->id", "<i class='icon icon-plus hl-primary text-primary'></i> &nbsp;" . $lang->doc->createLib, '', "class='text-muted iframe' data-width='1000px'");?>
                </div>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
        <?php else:?>
        <div class="col-sm-6">
          <div class="panel block-cfd" style="height: 280px">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $execution->name . $lang->execution->CFD;?></div>
              <?php if(common::hasPriv('execution', 'cfd')):?>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('execution', 'cfd', "executionID=$execution->id&type=task&withWeekend=false&begin=$begin&end=$end", mb_strtoupper($lang->more), '', "title=$lang->more");?></li>
              </nav>
              <?php endif;?>
            </div>
            <div class="panel-body">
              <?php if(common::hasPriv('execution', 'cfd')):?>
              <?php if(isset($chartData['labels']) and count($chartData['labels']) != 1): ?>
              <div id="cfdWrapper">
                <div id="cfdChart" style='height:240px;'></div>
              </div>
              <?php else:?>
              <div class="table-empty-tip">
                <p><span class="text-muted"><?php echo $lang->execution->noPrintData;?></span></p>
              </div>
              <?php endif;?>
              <?php endif;?>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel block-team" style="height: 280px">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $lang->execution->relatedMember;?></div>
              <?php if(common::hasPriv('execution', 'team')):?>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('execution', 'team', "executionID=$execution->id", mb_strtoupper($lang->more), '', "title=$lang->more");?></li>
              </nav>
              <?php endif;?>
            </div>
            <div class="panel-body">
              <div class="row row-grid">
                <?php $i = 9; $j = 0;?>
                <?php if($execution->PM):?>
                <?php $i--;?>
                <?php unset($teamMembers[$execution->PM]);?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $execution->PM);?> <span class="text-muted">（<?php echo $lang->execution->PM;?>）</span></div>
                <?php endif;?>
                <?php if($execution->PO):?>
                <?php $i--;?>
                <?php unset($teamMembers[$execution->PO]);?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $execution->PO);?> <span class="text-muted">（<?php echo $lang->execution->PO;?>）</span></div>
                <?php endif;?>
                <?php if($execution->QD):?>
                <?php $i--;?>
                <?php unset($teamMembers[$execution->QD]);?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $execution->QD);?> <span class="text-muted">（<?php echo $lang->execution->QD;?>）</span></div>
                <?php endif;?>
                <?php if($execution->RD):?>
                <?php $i--;?>
                <?php unset($teamMembers[$execution->RD]);?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $execution->RD);?> <span class="text-muted">（<?php echo $lang->execution->RD;?>）</span></div>
                <?php endif;?>

                <?php foreach($teamMembers as $teamMember):?>
                <?php if($j > $i) break;?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $teamMember->account);?></div>
                <?php $j++;?>
                <?php endforeach;?>
                <div class="col-xs-6">
                  <?php if($canBeChanged) common::printLink('execution', 'manageMembers', "executionID=$execution->id", "<i class='icon icon-plus hl-primary text-primary'></i> &nbsp;" . $lang->execution->manageMembers, '', "class='text-muted'");?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="panel block-dynamic" style="height: 280px">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $lang->execution->latestDynamic;?></div>
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
        <?php endif;?>
        <?php $this->printExtendFields($execution, 'div', "position=left&inForm=0");?>
        <div class="col-sm-12">
          <?php $blockHistory = true;?>
          <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=execution&objectID=$execution->id");?>
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
      <div class='main-actions'>
        <div class="btn-toolbar">
          <?php $browseLink = $this->session->executionList ? $this->session->executionList : inlink('browse', "executionID=$execution->id");?>
          <?php common::printBack($browseLink);?>
          <?php echo $this->execution->buildOperateMenu($execution, 'view');?>
        </div>
      </div>
    </div>
    <div class="col-4 side-col">
      <div class="row">
        <div class="col-sm-12">
          <div class="cell">
            <div class="detail">
              <?php $hiddenCode = (!isset($config->setCode) or $config->setCode == 0) ? 'hidden' : '';?>
              <h2 class="detail-title"><span class="label-id"><?php echo $execution->id;?></span> <span class="label label-light label-outline <?php echo $hiddenCode;?>"><?php echo $execution->code;?></span> <?php echo $execution->name;?></h2>
              <div class="detail-content article-content">
                <div><span class="text-limit hidden" data-limit-size="40"><?php echo $execution->desc;?></span><a class="text-primary text-limit-toggle small" data-text-expand="<?php echo $lang->expand;?>"  data-text-collapse="<?php echo $lang->collapse;?>"></a></div>
                <p>
                  <?php if($execution->deleted):?>
                  <span class='label label-danger label-outline'><?php echo $lang->execution->deleted;?></span>
                  <?php endif;?>
                  <?php if(!empty($execution->lifetime) and $execution->type != 'kanban' and $project->model != 'waterfall' and  $project->model != 'waterfallplus'):?>
                  <span class="label label-primary label-outline"><?php echo zget($lang->execution->lifeTimeList, $execution->lifetime);?></span>
                  <?php endif;?>
                  <?php if(isset($execution->delay)):?>
                  <span class="label label-danger label-outline"><?php echo $lang->execution->delayed;?></span>
                  <?php else:?>
                  <span class="label label-success label-outline"><?php echo $this->processStatus('execution', $execution);?></span>
                  <?php endif;?>
                </p>
              </div>
            </div>
            <?php if($this->config->systemMode == 'ALM'):?>
            <div class="detail">
              <div class="detail-title">
                <strong><?php echo $lang->project->parent;?></strong>
              </div>
              <div class="detail-content">
                <div class="row row-grid">
                  <div class="col-xs-12">
                  <?php if($execution->projectInfo->grade > 1):?>
                    <i class='icon icon-program text-muted'></i>
                    <?php
                    $names = '';
                    foreach($programList as $id => $name)
                    {
                        $names .=  common::hasPriv('program', 'product') ? html::a($this->createLink('program', 'product', "programID=$id"), $name) . '/ ' : $name . '/ ';
                    }
                    echo rtrim($names, '/ ');
                    ?>
                  <?php endif;?>
                  </div>
                </div>
              </div>
            </div>
            <?php endif;?>
            <div class="detail">
              <div class="detail-title">
                <strong><?php echo $lang->project->project;?></strong>
              </div>
              <div class="detail-content">
                <div class="row row-grid">
                  <div class="col-xs-12">
                    <i class='icon icon-project text-muted'></i>
                    <?php echo common::hasPriv('project', 'index') ? html::a($this->createLink('project', 'index', "projectID=$execution->project", '', '', $execution->project), $execution->projectInfo->name) : $execution->projectInfo->name;?>
                  </div>
                </div>
              </div>
            </div>
            <?php if($execution->projectInfo->hasProduct): ?>
            <div class="detail">
              <div class="detail-title">
                <strong><?php echo $lang->execution->manageProducts;?></strong>
                <?php if(common::hasPriv('execution', 'manageproducts') and $execution->type != 'stage' and $project->model != 'waterfallplus') common::printLink('execution', 'manageproducts', "executionID=$execution->id", '<i class="icon icon-more icon-sm"></i>', '', "class='btn btn-link pull-right muted'");?>
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
            <?php endif;?>
            <?php if($features['plan']):?>
            <div class="detail">
              <div class="detail-title"><strong><?php echo $lang->execution->linkPlan;?></strong></div>
              <div class="detail-content">
                <div class="row row-grid">
                <?php
                foreach($products as $productID => $product)
                {
                    foreach($product->plans as $planIDList)
                    {
                        $planIDList = explode(',', $planIDList);
                        foreach($planIDList as $planID)
                        {
                            if(isset($planGroups[$productID][$planID])) echo '<div class="col-xs-12">' . "<i class='icon icon-calendar text-muted'></i> " . html::a($this->createLink('productplan', 'view', "planID={$planID}"), $product->name . '/' . $planGroups[$productID][$planID], '_self', $execution->projectInfo->hasProduct ? '' : "data-app='project'") . '</div>';
                        }
                    }
                }
                ?>
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
                        <?php $progress = ($execution->totalConsumed + $execution->totalLeft) ? floor($execution->totalConsumed / ($execution->totalConsumed + $execution->totalLeft) * 1000) / 1000 * 100 : 0;?>
                        <?php echo $lang->execution->progress;?> <?php echo $progress . $lang->percent;?> &nbsp;
                        <div class="progress inline-block">
                          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress . $lang->percent;?>"></div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->execution->begin;?></th>
                      <td><?php echo $execution->begin;?></td>
                      <th><?php echo $lang->execution->realBeganAB;?></th>
                      <td><?php echo $execution->realBegan == '0000-00-00' ? '' : $execution->realBegan;?></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->execution->end;?></th>
                      <td><?php echo $execution->end;?></td>
                      <th><?php echo $lang->execution->realEndAB;?></th>
                      <td><?php echo $execution->realEnd == '0000-00-00' ? '' : $execution->realEnd;?></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->execution->totalEstimate;?></th>
                      <td><?php echo (float)$execution->totalEstimate . $lang->execution->workHour;?></td>
                      <th><?php echo $lang->execution->totalDays;?></th>
                      <td><?php echo (float)$execution->days . $lang->execution->day;?></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->execution->totalConsumed;?></th>
                      <td><?php echo (float)$execution->totalConsumed . $lang->execution->workHour;?></td>
                      <th><?php echo $lang->execution->totalHours;?></th>
                      <td><?php echo (float)$execution->totalHours . $lang->execution->workHour;?></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->execution->totalLeft;?></th>
                      <td><?php echo (float)$execution->totalLeft . $lang->execution->workHour;?></td>
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
                    <tr>
                      <?php if($features['story']):?>
                      <th><?php echo $lang->story->common;?></th>
                      <td><?php echo $statData->storyCount;?></td>
                      <?php endif;?>
                      <th><?php echo $lang->task->common;?></th>
                      <td><?php echo $statData->taskCount;?></td>
                      <?php if($features['qa']):?>
                      <th><?php echo $lang->bug->common;?></th>
                      <td><?php echo $statData->bugCount;?></td>
                      <?php endif;?>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="detail">
              <div class="detail-title"><strong><?php echo $lang->execution->acl;?></strong></div>
              <div class="detail-content">
                <p><?php echo $lang->execution->aclList[$execution->acl];?></p>
              </div>
            </div>
            <?php $this->printExtendFields($execution, 'div', "position=right&inForm=0&inCell=1");?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="mainActions" class='main-actions'>
  <nav class="container"></nav>
<script>
$(function()
{
    <?php if(isset($execution->type) and $execution->type != 'kanban'):?>
    var data =
    {
        labels: <?php echo json_encode($chartData['labels'])?>,
        datasets: [
        {
            label: "<?php echo $lang->execution->charts->burn->graph->reference;?>",
            color: "#F1F1F1",
            pointColor: '#D8D8D8',
            pointStrokeColor: '#D8D8D8',
            pointHighlightStroke: '#D8D8D8',
            fillColor: 'transparent',
            pointHighlightFill: '#fff',
            data: <?php echo $chartData['baseLine']?>
        },
        {
            label: "<?php echo $lang->execution->charts->burn->graph->actuality;?>",
            color: "#006AF1",
            pointStrokeColor: '#006AF1',
            pointHighlightStroke: '#006AF1',
            pointColor: '#006AF1',
            fillColor: 'rgba(0,106,241, .07)',
            pointHighlightFill: '#fff',
            data: <?php echo $chartData['burnLine']?>
        }]
    };

    var delaySets =
    {
        label: "<?php echo $lang->execution->charts->burn->graph->delay;?>",
        color: 'red',
        pointStrokeColor: 'red',
        pointHighlightStroke: 'red',
        pointColor: 'red',
        fillColor: 'rgba(0,106,241, .07)',
        pointHighlightFill: '#fff',
        data: <?php echo isset($chartData['delayLine']) ? $chartData['delayLine'] : '[]';?>
    }
    if(type.match('withdelay')) data.datasets.push(delaySets);

    var burnChart = $("#burnCanvas").lineChart(data,
    {
        pointDotStrokeWidth: 2,
        pointDotRadius: 2,
        datasetStrokeWidth: 2,
        datasetFill: true,
        datasetStroke: true,
        scaleShowBeyondLine: false,
        responsive: true,
        bezierCurve: false,
        scaleFontColor: '#838A9D',
        tooltipXPadding: 10,
        tooltipYPadding: 10,
        multiTooltipTitleTemplate: '<%= label %> <?php echo $lang->execution->workHour;?> /h',
        multiTooltipTemplate: "<%if (datasetLabel){%><%=datasetLabel%>: <%}%><%= value %>",
    });
    <?php else:?>
    var i      = 0;
    var series = [];
    var colors = ['#33B4DB', '#7ECF69', '#FFC73A', '#FF5A61', '#50C8D0', '#AF5AFF', '#4EA3FF', '#FF8C5A', '#6C73FF'];

    var chartDom = document.getElementById('cfdChart');
    if(Object.keys(chartData).length && chartDom)
    {
        $.each(chartData['line'], function(label, set)
        {
            series.push({
                name: label,
                type: 'line',
                stack: 'Total',
                color: colors[i],
                symbolSize: 1,
                areaStyle: {
                    color: colors[i],
                    opacity: 0.2
                },
                itemStyle: {
                    normal: {
                        lineStyle:{
                            width: 1
                        }
                    }
                },
                data: eval(set)
            })
            i ++;
        })

        var CFD = echarts.init(chartDom);
        var option;

        option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'cross',
                    crossStyle: {
                        width: 0,
                    },
                    label: {
                      backgroundColor: '#6a7985'
                    }
                },
                formatter: function(params)
                {
                    var newParams     = [];
                    var tooltipString = [];
                    newParams = params.reverse();
                    newParams.forEach((p) => {
                        const cont = p.marker + ' ' + p.seriesName + ': ' + p.value + '<br/>';
                        tooltipString.push(cont);
                    });
                    return tooltipString.join('');
                },
                textStyle: {
                    fontWeight: 100
                }
            },
            grid: {
                left: '3%',
                right: '5%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: [
                {
                    type: 'category',
                    boundaryGap: false,
                    data: chartData['labels'],
                    axisLabel: {
                        interval: 0
                    },
                    axisLine:
                    {
                        show: true,
                        lineStyle:
                        {
                            color: '#999',
                            width: 1
                        }
                    }
                }],
            yAxis: [
              {
                  type: 'value',
                  minInterval: 1,
                  name: YUnit,
                  nameTextStyle:
                  {
                    fontWeight: 'normal'
                  },
                  axisPointer:
                  {
                      label:
                      {
                          show: true,
                          precision: 0
                      },
                  },
                  axisLine:
                  {
                      show: true,
                      lineStyle:
                      {
                          color: '#999',
                          width: 1
                      }
                  }
              }
            ],
            series: series,
        };

        option && CFD.setOption(option);
        window.addEventListener('resize', CFD.resize);
    }
    <?php endif;?>
});
</script>
<?php include '../../common/view/footer.html.php';?>
