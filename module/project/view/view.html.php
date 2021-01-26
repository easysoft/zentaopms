<?php
/**
 * The view method view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: view.html.php 4594 2013-03-13 06:16:02Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
  <div id='mainContent' class="main-row" <?php if(isonlybody()) echo 'style="padding-top: 0px;"';?>>
    <div class="col-8 main-col">
      <div class="row">
        <div class="col-sm-6">
          <div class="panel block-burn" style="height: 280px">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $execution->name . $lang->project->burn;?></div>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('project', 'burn', "projectID=$execution->id", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
              </nav>
            </div>
            <div class="panel-body">
              <?php if(common::hasPriv('project', 'burn')):?>
              <div id="burnWrapper">
                <div id="burnChart">
                  <canvas id="burnCanvas"></canvas>
                </div>
                <div id="burnYUnit">(<?php echo $lang->project->workHour;?>)</div>
                <div id="burnLegend">
                  <div class="line-ref"><?php echo $lang->project->charts->burn->graph->reference;?></div>
                  <div class="line-real"><?php echo $lang->project->charts->burn->graph->actuality;?></div>
                </div>
              </div>
              <?php endif;?>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel block-dynamic" style="height: 280px">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $lang->project->latestDynamic;?></div>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('project', 'dynamic', "projectID=$execution->id&type=all", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
              </nav>
            </div>
            <div class="panel-body scrollbar-hover">
              <ul class="timeline timeline-tag-left no-margin">
                <?php foreach($dynamics as $action):?>
                <li <?php if($action->major) echo "class='active'";?>>
                  <div class='text-ellipsis'>
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
              <div class="panel-title"><?php echo $lang->project->relatedMember;?></div>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('project', 'team', "projectID=$execution->id", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
              </nav>
            </div>
            <div class="panel-body">
              <div class="row row-grid">
                <?php $i = 9; $j = 0;?>
                <?php if($execution->PM):?>
                <?php $i--;?>
                <?php unset($teamMembers[$execution->PM]);?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $execution->PM);?> <span class="text-muted">（<?php echo $lang->project->PM;?>）</span></div>
                <?php endif;?>
                <?php if($execution->PO):?>
                <?php $i--;?>
                <?php unset($teamMembers[$execution->PO]);?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $execution->PO);?> <span class="text-muted">（<?php echo $lang->project->PO;?>）</span></div>
                <?php endif;?>
                <?php if($execution->QD):?>
                <?php $i--;?>
                <?php unset($teamMembers[$execution->QD]);?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $execution->QD);?> <span class="text-muted">（<?php echo $lang->project->QD;?>）</span></div>
                <?php endif;?>
                <?php if($execution->RD):?>
                <?php $i--;?>
                <?php unset($teamMembers[$execution->RD]);?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $execution->RD);?> <span class="text-muted">（<?php echo $lang->project->RD;?>）</span></div>
                <?php endif;?>

                <?php if(common::hasPriv('project', 'team')):?>
                <?php foreach($teamMembers as $teamMember):?>
                <?php if($j > $i) break;?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $teamMember->account);?></div>
                <?php $j++;?>
                <?php endforeach;?>
                <div class="col-xs-6">
                  <?php if($canBeChanged) common::printLink('project', 'manageMembers', "projectID=$execution->id", "<i class='icon icon-plus hl-primary text-primary'></i> &nbsp;" . $lang->project->manageMembers, '', "class='text-muted'");?>
                </div>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel block-docs" style="height: 240px">
            <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->project->doclib;?></div>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('doc', 'objectLibs', "type=project&projectID=$execution->id&from=project", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
              </nav>
            </div>
            <div class="panel-body">
              <div class="row row-grid">
                <?php if(common::hasPriv('doc', 'objectLibs')):?>
                <?php $i = 0;?>
                <?php foreach($docLibs as $libID => $docLib):?>
                <?php if($i > 8) break;?>
                <div class="col-xs-6">
                  <?php if($libID == 'files'):?>
                  <?php echo html::a($this->createLink('doc', 'showFiles', "type=project&objectID=$execution->id"), "<i class='icon icon-folder text-yellow'></i> " . $docLib->name);?>
                  <?php else:?>
                  <?php echo html::a($this->createLink('doc', 'browse', "libID=$libID&browseType=all&param=0&orderBy=id_desc&from=project"), "<i class='icon icon-folder text-yellow'></i> " . $docLib->name);?>
                  <?php endif;?>
                </div>
                <?php $i++;?>
                <?php endforeach;?>
                <div class="col-xs-6">
                  <?php if($canBeChanged) common::printLink('doc', 'createLib', "type=project&objectID=$execution->id", "<i class='icon icon-plus hl-primary text-primary'></i> &nbsp;" . $lang->doc->createLib, '', "class='text-muted iframe' data-width='1000px'");?>
                </div>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
        <?php $this->printExtendFields($execution, 'div', "position=left&inForm=0");?>
        <div class="col-sm-12">
          <?php $blockHistory = true;?>
          <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=execution&objectID=$execution->id");?>
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
      <div class='main-actions'>
        <div class="btn-toolbar">
          <?php
          $params = "project=$execution->id";
          $browseLink = $this->session->projectList ? $this->session->projectList : inlink('browse', "projectID=$execution->id");
          common::printBack($browseLink);
          if(!$execution->deleted)
          {
              echo "<div class='divider'></div>";
              common::printIcon('project', 'start',    "projectID=$execution->id", $execution, 'button', '', '', 'iframe', true);
              common::printIcon('project', 'activate', "projectID=$execution->id", $execution, 'button', '', '', 'iframe', true);
              common::printIcon('project', 'putoff',   "projectID=$execution->id", $execution, 'button', '', '', 'iframe', true);
              common::printIcon('project', 'suspend',  "projectID=$execution->id", $execution, 'button', '', '', 'iframe', true);
              common::printIcon('project', 'close',    "projectID=$execution->id", $execution, 'button', '', '', 'iframe', true);

              echo $this->buildOperateMenu($execution, 'view');

              echo "<div class='divider'></div>";
              common::printIcon('project', 'edit', $params, $execution);
              common::printIcon('project', 'delete', $params, $execution, 'button', 'trash', 'hiddenwin');
          }
          ?>
        </div>
      </div>
    </div>
    <div class="col-4 side-col">
      <div class="row">
        <div class="col-sm-12">
          <div class="cell">
            <div class="detail">
              <h2 class="detail-title"><span class="label-id"><?php echo $execution->id;?></span> <span class="label label-light label-outline"><?php echo $execution->code;?></span> <?php echo $execution->name;?></h2>
              <div class="detail-content article-content">
                <p><span class="text-limit" data-limit-size="40"><?php echo $execution->desc;?></span><a class="text-primary text-limit-toggle small" data-text-expand="<?php echo $lang->expand;?>"  data-text-collapse="<?php echo $lang->collapse;?>"></a></p>
                <p>
                  <?php if($execution->deleted):?>
                  <span class='label label-danger label-outline'><?php echo $lang->project->deleted;?></span>
                  <?php endif; ?>
                  <span class="label label-primary label-outline"><?php echo zget($lang->program->PRJLifeTimeList, $execution->lifetime);?></span>
                  <?php if(isset($execution->delay)):?>
                  <span class="label label-danger label-outline"><?php echo $lang->project->delayed;?></span>
                  <?php else:?>
                  <span class="label label-success label-outline"><?php echo $this->processStatus('project', $execution);?></span>
                  <?php endif;?>
                </p>
              </div>
            </div>
            <div class="detail">
              <div class="detail-title">
                <strong><?php echo $lang->project->manageProducts;?></strong>
                <?php common::printLink('project', 'manageproducts', "projectID=$execution->id", '<i class="icon icon-more icon-sm"></i>', '', "class='btn btn-link pull-right muted'");?>
              </div>
              <div class="detail-content">
                <div class="row row-grid">
                  <?php foreach($products as $productID => $product):?>
                  <?php $branchName = isset($branchGroups[$productID][$product->branch]) ? '/' . $branchGroups[$productID][$product->branch] : '';?>
                  <div class="col-xs-6">
                    <?php echo html::a($this->createLink('product', 'browse', "productID=$productID&branch=$product->branch"), "<i class='icon icon-cube text-muted'></i> " . $product->name . $branchName);?>
                  </div>
                  <?php endforeach;?>
                </div>
              </div>
            </div>
            <div class="detail">
              <div class="detail-title"><strong><?php echo $lang->project->linkPlan;?></strong></div>
              <div class="detail-content">
                <div class="row row-grid">
                  <?php foreach($products as $productID => $product):?>
                  <?php if(isset($planGroups[$productID][$product->plan])):?>
                  <div class="col-xs-12"><?php echo html::a($this->createLink('productplan', 'view', "planID={$product->plan}"), $product->name . '/' . $planGroups[$productID][$product->plan]);?></div>
                  <?php endif;?>
                  <?php endforeach;?>
                </div>
              </div>
            </div>
            <div class='detail'>
              <div class='detail-title'><strong><?php echo $lang->project->lblStats;?></strong></div>
              <div class="detail-content">
                <table class='table table-data data-stats'>
                  <tbody>
                    <tr class='statsTr'><td class='w-100px'></td><td></td><td></td><td></td></tr>
                    <tr>
                      <td colspan="4">
                        <?php $progress = ($execution->totalConsumed + $execution->totalLeft) ? floor($execution->totalConsumed / ($execution->totalConsumed + $execution->totalLeft) * 1000) / 1000 * 100 : 0;?>
                        <?php echo $lang->project->progress;?> <em><?php echo $progress . $lang->percent;?></em> &nbsp;
                        <div class="progress inline-block">
                          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress . $lang->percent;?>"></div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->project->begin;?></th>
                      <td><?php echo $execution->begin;?></td>
                      <th><?php echo $lang->project->totalEstimate;?></th>
                      <td><em><?php echo (float)$execution->totalEstimate . $lang->project->workHour;?></em></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->project->end;?></th>
                      <td><?php echo $execution->end;?></td>
                      <th><?php echo $lang->project->totalConsumed;?></th>
                      <td><em><?php echo (float)$execution->totalConsumed . $lang->project->workHour;?></em></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->project->totalDays;?></th>
                      <td><?php echo $execution->days;?></td>
                      <th><?php echo $lang->project->totalLeft;?></th>
                      <td><em><?php echo (float)$execution->totalLeft . $lang->project->workHour;?></em></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->project->totalHours;?></th>
                      <td><em><?php echo (float)$execution->totalHours . $lang->project->workHour;?></em></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="detail">
              <div class="detail-title"><strong><?php echo $lang->project->basicInfo;?></strong></div>
              <div class="detail-content">
                <table class="table table-data data-basic">
                  <tbody>
                    <tr>
                      <th><?php echo $lang->story->common;?></th>
                      <td><em><?php echo $statData->storyCount;?></em></td>
                      <th><?php echo $lang->task->common;?></th>
                      <td><em><?php echo $statData->taskCount;?></em></td>
                      <th><?php echo $lang->bug->common;?></th>
                      <td><em><?php echo $statData->bugCount;?></em></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="detail">
              <div class="detail-title"><strong><?php echo $lang->project->acl;?></strong></div>
              <div class="detail-content">
                <p><?php echo $lang->project->aclList[$execution->acl];?></p>
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
    var data =
    {
        labels: <?php echo json_encode($chartData['labels'])?>,
        datasets: [
        {
            label: "<?php echo $lang->project->charts->burn->graph->reference;?>",
            color: "#F1F1F1",
            pointColor: '#D8D8D8',
            pointStrokeColor: '#D8D8D8',
            pointHighlightStroke: '#D8D8D8',
            fillColor: 'transparent',
            pointHighlightFill: '#fff',
            data: <?php echo $chartData['baseLine']?>
        },
        {
            label: "<?php echo $lang->project->charts->burn->graph->actuality;?>",
            color: "#006AF1",
            pointStrokeColor: '#006AF1',
            pointHighlightStroke: '#006AF1',
            pointColor: '#006AF1',
            fillColor: 'rgba(0,106,241, .07)',
            pointHighlightFill: '#fff',
            data: <?php echo $chartData['burnLine']?>
        }]
    };

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
        multiTooltipTitleTemplate: '<%= label %> <?php echo $lang->project->workHour;?> /h',
        multiTooltipTemplate: "<%if (datasetLabel){%><%=datasetLabel%>: <%}%><%= value %>",
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
