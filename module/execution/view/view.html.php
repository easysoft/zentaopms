<?php
/**
 * The view method view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: view.html.php 4594 2013-03-13 06:16:02Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php $style = isonlybody() ? 'style="padding-top: 0px;"' : '';?>
  <div id='mainContent' class="main-row" <?php echo $style;?>>
    <div class="col-8 main-col">
      <div class="row">
        <div class="col-sm-6">
          <div class="panel block-burn" style="height: 280px">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $execution->name . $lang->execution->burn;?></div>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('execution', 'burn', "executionID=$execution->id", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
              </nav>
            </div>
            <div class="panel-body">
              <?php if(common::hasPriv('execution', 'burn')):?>
              <div id="burnWrapper">
                <div id="burnChart">
                  <canvas id="burnCanvas"></canvas>
                </div>
                <div id="burnYUnit">(<?php echo $lang->execution->workHour;?>)</div>
                <div id="burnLegend">
                  <div class="line-ref"><?php echo $lang->execution->charts->burn->graph->reference;?></div>
                  <div class="line-real"><?php echo $lang->execution->charts->burn->graph->actuality;?></div>
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
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('execution', 'dynamic', "executionID=$execution->id&type=all", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
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
              <div class="panel-title"><?php echo $lang->execution->relatedMember;?></div>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('execution', 'team', "executionID=$execution->id", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
              </nav>
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

                <?php if(common::hasPriv('execution', 'team')):?>
                <?php foreach($teamMembers as $teamMember):?>
                <?php if($j > $i) break;?>
                <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $teamMember->account);?></div>
                <?php $j++;?>
                <?php endforeach;?>
                <div class="col-xs-6">
                  <?php if($canBeChanged) common::printLink('execution', 'manageMembers', "executionID=$execution->id", "<i class='icon icon-plus hl-primary text-primary'></i> &nbsp;" . $lang->execution->manageMembers, '', "class='text-muted'");?>
                </div>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel block-docs" style="height: 240px">
            <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->execution->doclib;?></div>
              <nav class="panel-actions nav nav-default">
                <li><?php common::printLink('doc', 'objectLibs', "type=execution&executionID=$execution->id&from=execution", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
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
                  <?php echo html::a($this->createLink('doc', 'showFiles', "type=execution&objectID=$execution->id"), "<i class='icon icon-folder text-yellow'></i> " . $docLib->name);?>
                  <?php else:?>
                  <?php echo html::a($this->createLink('doc', 'objectLibs', "type=execution&objectID={$execution->id}&libID=$libID"), "<i class='icon icon-folder text-yellow'></i> " . $docLib->name, '', "data-app='execution'");?>
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
          $params = "execution=$execution->id";
          $browseLink = $this->session->executionList ? $this->session->executionList : inlink('browse', "executionID=$execution->id");
          common::printBack($browseLink);
          if(!$execution->deleted)
          {
              echo "<div class='divider'></div>";
              common::printIcon('execution', 'start',    "executionID=$execution->id", $execution, 'button', '', '', 'iframe', true);
              common::printIcon('execution', 'activate', "executionID=$execution->id", $execution, 'button', '', '', 'iframe', true);
              common::printIcon('execution', 'putoff',   "executionID=$execution->id", $execution, 'button', '', '', 'iframe', true);
              common::printIcon('execution', 'suspend',  "executionID=$execution->id", $execution, 'button', '', '', 'iframe', true);
              common::printIcon('execution', 'close',    "executionID=$execution->id", $execution, 'button', '', '', 'iframe', true);

              echo $this->buildOperateMenu($execution, 'view');

              echo "<div class='divider'></div>";
              common::printIcon('execution', 'edit', $params, $execution);
              common::printIcon('execution', 'delete', $params, $execution, 'button', 'trash', 'hiddenwin');
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
                  <span class='label label-danger label-outline'><?php echo $lang->execution->deleted;?></span>
                  <?php endif; ?>
                  <span class="label label-primary label-outline"><?php echo zget($lang->execution->lifeTimeList, $execution->lifetime);?></span>
                  <?php if(isset($execution->delay)):?>
                  <span class="label label-danger label-outline"><?php echo $lang->execution->delayed;?></span>
                  <?php else:?>
                  <span class="label label-success label-outline"><?php echo $this->processStatus('execution', $execution);?></span>
                  <?php endif;?>
                </p>
              </div>
            </div>
            <div class="detail">
              <div class="detail-title">
                <strong><?php echo $lang->execution->manageProducts;?></strong>
                <?php common::printLink('execution', 'manageproducts', "executionID=$execution->id", '<i class="icon icon-more icon-sm"></i>', '', "class='btn btn-link pull-right muted'");?>
              </div>
              <div class="detail-content">
                <div class="row row-grid">
                  <?php foreach($products as $productID => $product):?>
                  <?php $branchName = isset($branchGroups[$productID][$product->branch]) ? '/' . $branchGroups[$productID][$product->branch] : '';?>
                  <div class="col-xs-6">
                    <?php echo html::a($this->createLink('product', 'browse', "productID=$productID&branch=$product->branch"), "<i class='icon icon-product text-muted'></i> " . $product->name . $branchName);?>
                  </div>
                  <?php endforeach;?>
                </div>
              </div>
            </div>
            <div class="detail">
              <div class="detail-title"><strong><?php echo $lang->execution->linkPlan;?></strong></div>
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
              <div class='detail-title'><strong><?php echo $lang->execution->lblStats;?></strong></div>
              <div class="detail-content">
                <table class='table table-data data-stats'>
                  <tbody>
                    <tr class='statsTr'><td class='w-100px'></td><td></td><td></td><td></td></tr>
                    <tr>
                      <td colspan="4">
                        <?php $progress = ($execution->totalConsumed + $execution->totalLeft) ? floor($execution->totalConsumed / ($execution->totalConsumed + $execution->totalLeft) * 1000) / 1000 * 100 : 0;?>
                        <?php echo $lang->execution->progress;?> <em><?php echo $progress . $lang->percent;?></em> &nbsp;
                        <div class="progress inline-block">
                          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress . $lang->percent;?>"></div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->execution->begin;?></th>
                      <td><?php echo $execution->begin;?></td>
                      <th><?php echo $lang->execution->totalEstimate;?></th>
                      <td><em><?php echo (float)$execution->totalEstimate . $lang->execution->workHour;?></em></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->execution->end;?></th>
                      <td><?php echo $execution->end;?></td>
                      <th><?php echo $lang->execution->totalConsumed;?></th>
                      <td><em><?php echo (float)$execution->totalConsumed . $lang->execution->workHour;?></em></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->execution->totalDays;?></th>
                      <td><?php echo $execution->days;?></td>
                      <th><?php echo $lang->execution->totalLeft;?></th>
                      <td><em><?php echo (float)$execution->totalLeft . $lang->execution->workHour;?></em></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->execution->totalHours;?></th>
                      <td><em><?php echo (float)$execution->totalHours . $lang->execution->workHour;?></em></td>
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
});
</script>
<?php include '../../common/view/footer.html.php';?>
