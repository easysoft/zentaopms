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
  <div id='mainContent' class="main-row">
    <div class="col-8 main-col">
      <div class="row">
        <div class="col-sm-6">
          <div class="panel block-burn" style="height: 280px">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $project->name . $lang->project->burn;?></div>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('project', 'burn', "projectID=$project->id", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
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
                <li><?php common::printLink('project', 'dynamic', "projectID=$project->id&type=all", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
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
                <li><?php common::printLink('project', 'team', "projectID=$project->id", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
              </nav>
            </div>
            <div class="panel-body">
              <div class="row row-grid">
                <?php $i = 9; $j = 0;?>
                <?php if($config->global->flow != 'onlyTask'):?>
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
                <?php endif;?>

                <?php if(common::hasPriv('project', 'team')):?>
                <?php foreach($teamMembers as $teamMember):?>
                <?php if($j > $i) break;?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $teamMember->account);?></div>
                <?php $j++;?>
                <?php endforeach;?>
                <div class="col-xs-6">
                  <?php common::printLink('project', 'manageMembers', "projectID=$project->id", "<i class='icon icon-plus hl-primary text-primary'></i> &nbsp;" . $lang->project->manageMembers, '', "class='text-muted'");?>
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
                <li><?php common::printLink('doc', 'objectLibs', "type=project&projectID=$project->id&from=project", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
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
                  <?php echo html::a($this->createLink('doc', 'showFiles', "type=project&objectID=$project->id"), "<i class='icon icon-folder text-yellow'></i> " . $docLib->name);?>
                  <?php else:?>
                  <?php echo html::a($this->createLink('doc', 'browse', "libID=$libID&browseType=all&param=0&orderBy=id_desc&from=project"), "<i class='icon icon-folder text-yellow'></i> " . $docLib->name);?>
                  <?php endif;?>
                </div>
                <?php $i++;?>
                <?php endforeach;?>
                <div class="col-xs-6">
                  <?php common::printLink('doc', 'createLib', "type=project&objectID=$project->id", "<i class='icon icon-plus hl-primary text-primary'></i> &nbsp;" . $lang->doc->createLib, '', "class='text-muted iframe' data-width='1000px'");?>
                </div>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
        <?php $this->printExtendFields($project, 'div', "position=left&inForm=0");?>
        <div class="col-sm-12">
          <?php $blockHistory = true;?>
          <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=project&objectID=$project->id");?>
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
      <div class='main-actions'>
        <div class="btn-toolbar">
          <?php
          $params = "project=$project->id";
          $browseLink = $this->session->projectList ? $this->session->projectList : inlink('browse', "projectID=$project->id");
          common::printBack($browseLink);
          if(!$project->deleted)
          {
              echo "<div class='divider'></div>";
              common::printIcon('project', 'start',    "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
              common::printIcon('project', 'activate', "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
              common::printIcon('project', 'putoff',   "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
              common::printIcon('project', 'suspend',  "projectID=$project->id", $project, 'button', '', '', 'iframe', true);
              common::printIcon('project', 'close',    "projectID=$project->id", $project, 'button', '', '', 'iframe', true);

              echo $this->buildOperateMenu($project, 'view');

              echo "<div class='divider'></div>";
              common::printIcon('project', 'edit', $params, $project);
              common::printIcon('project', 'delete', $params, $project, 'button', 'trash', 'hiddenwin');
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
              <h2 class="detail-title"><span class="label-id"><?php echo $project->id;?></span> <span class="label label-light label-outline"><?php echo $project->code;?></span> <?php echo $project->name;?></h2>
              <div class="detail-content article-content">
                <p><span class="text-limit" data-limit-size="40"><?php echo $project->desc;?></span><a class="text-primary text-limit-toggle small" data-text-expand="<?php echo $lang->expand;?>"  data-text-collapse="<?php echo $lang->collapse;?>"></a></p>
                <p>
                  <?php if($project->deleted):?>
                  <span class='label label-danger label-outline'><?php echo $lang->project->deleted;?></span>
                  <?php endif; ?>
                  <span class="label label-primary label-outline"><?php echo zget($lang->project->typeList, $project->type);?></span>
                  <?php if(isset($project->delay)):?>
                  <span class="label label-danger label-outline"><?php echo $lang->project->delayed;?></span>
                  <?php else:?>
                  <span class="label label-success label-outline"><?php echo $this->processStatus('project', $project);?></span>
                  <?php endif;?>
                </p>
              </div>
            </div>
            <?php if($config->global->flow != 'onlyTask'):?>
            <div class="detail">
              <div class="detail-title">
                <strong><?php echo $lang->project->manageProducts;?></strong>
                <?php common::printLink('project', 'manageproducts', "projectID=$project->id", '<i class="icon icon-more icon-sm"></i>', '', "class='btn btn-link pull-right muted'");?>
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
            <?php endif;?>
            <div class='detail'>
              <div class='detail-title'><strong><?php echo $lang->project->lblStats;?></strong></div>
              <div class="detail-content">
                <table class='table table-data data-stats'>
                  <tbody>
                    <tr class='statsTr'><td class='w-100px'></td><td></td><td></td><td></td></tr>
                    <tr>
                      <td colspan="4">
                        <?php $progress = ($project->totalConsumed + $project->totalLeft) ? round($project->totalConsumed / ($project->totalConsumed + $project->totalLeft), 3) * 100 : 0;?>
                        <?php echo $lang->project->progress;?> <em><?php echo $progress . $lang->percent;?></em> &nbsp;
                        <div class="progress inline-block">
                          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress . $lang->percent;?>"></div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->project->begin;?></th>
                      <td><?php echo $project->begin;?></td>
                      <th><?php echo $lang->project->totalEstimate;?></th>
                      <td><em><?php echo (float)$project->totalEstimate . $lang->project->workHour;?></em></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->project->end;?></th>
                      <td><?php echo $project->end;?></td>
                      <th><?php echo $lang->project->totalConsumed;?></th>
                      <td><em><?php echo (float)$project->totalConsumed . $lang->project->workHour;?></em></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->project->totalDays;?></th>
                      <td><?php echo $project->days;?></td>
                      <th><?php echo $lang->project->totalLeft;?></th>
                      <td><em><?php echo (float)$project->totalLeft . $lang->project->workHour;?></em></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->project->totalHours;?></th>
                      <td><em><?php echo (float)$project->totalHours . $lang->project->workHour;?></em></td>
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
                <p><?php echo $lang->project->aclList[$project->acl];?></p>
                <?php if($project->acl == 'custom'):?>
                <p>
                  <?php
                  $whitelist = explode(',', $project->whitelist);
                  foreach($whitelist as $groupID) if(isset($groups[$groupID])) echo $groups[$groupID] . '&nbsp;';
                  ?>
                </p>
                <?php endif;?>
              </div>
            </div>
            <?php $this->printExtendFields($project, 'div', "position=right&inForm=0&inCell=1");?>
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
