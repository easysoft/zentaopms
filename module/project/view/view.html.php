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
        <div class="panel block-dynamic" style="height: 280px">
          <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->execution->latestDynamic;?></div>
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
        <div class="panel block-team" style="height: 280px">
          <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->execution->relatedMember;?></div>
            <nav class="panel-actions nav nav-default">
              <li><?php common::printLink('project', 'manageMembers', "projectID=$project->id", '<i class="icon icon-more icon-sm"></i>', '', "title=$lang->more");?></li>
            </nav>
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
      <div class="col-sm-12">
        <?php $blockHistory = true;?>
        <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=project&objectID=$project->id");?>
        <?php include '../../common/view/action.html.php';?>
      </div>
    </div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php
        $params     = "project=$project->id";
        $browseLink = $this->session->projectList ? $this->session->projectList : inlink('browse');
        common::printBack($browseLink);
        if(!$project->deleted)
        {
            echo "<div class='divider'></div>";
            common::printIcon('project', 'start',    "projectID=$project->id", $project, 'button', 'play', '', 'iframe', true, '', $lang->project->start);
            common::printIcon('project', 'activate', "projectID=$project->id", $project, 'button', 'magic', '', 'iframe', true, '', $lang->project->activate);
            common::printIcon('project', 'suspend',  "projectID=$project->id", $project, 'button', 'pause', '', 'iframe', true, '', $lang->project->suspend);
            common::printIcon('project', 'close',    "projectID=$project->id", $project, 'button', 'off', '', 'iframe', true, '', $lang->close);

            echo $this->buildOperateMenu($project, 'view');

            echo "<div class='divider'></div>";
            common::printIcon('project', 'edit', $params . '&from=projectView', $project, 'button', 'edit', '', '', '', '', $lang->edit);
            common::printIcon('project', 'delete', $params, $project, 'button', 'trash', 'hiddenwin', '', '', '', $lang->delete);
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
                <span class="label label-primary label-outline"><?php echo zget($lang->execution->lifeTimeList, $project->lifetime);?></span>
                <?php if(isset($project->delay)):?>
                <span class="label label-danger label-outline"><?php echo $lang->project->delayed;?></span>
                <?php else:?>
                <span class="label label-success label-outline"><?php echo $this->processStatus('project', $project);?></span>
                <?php endif;?>
              </p>
            </div>
          </div>
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
                      <?php $progress = ($workhour->totalConsumed + $workhour->totalLeft) ? floor($workhour->totalConsumed / ($workhour->totalConsumed + $workhour->totalLeft) * 1000) / 1000 * 100 : 0;?>
                      <?php echo $lang->project->progress;?> <em><?php echo $progress . $lang->percent;?></em> &nbsp;
                      <div class="progress inline-block">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress . $lang->percent;?>"></div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->project->begin;?></th>
                    <td><?php echo $project->begin;?></td>
                    <th><?php echo $lang->execution->totalEstimate;?></th>
                    <td><em><?php echo (float)$workhour->totalEstimate . $lang->execution->workHour;?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->project->end;?></th>
                    <td><?php echo $project->end;?></td>
                    <th><?php echo $lang->execution->totalConsumed;?></th>
                    <td><em><?php echo (float)$workhour->totalConsumed . $lang->execution->workHour;?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->execution->totalDays;?></th>
                    <td><?php echo $project->days;?></td>
                    <th><?php echo $lang->execution->totalLeft;?></th>
                    <td><em><?php echo (float)$workhour->totalLeft . $lang->execution->workHour;?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->execution->totalHours;?></th>
                    <td><em><?php echo (float)$workhour->totalHours . $lang->execution->workHour;?></em></td>
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
            <div class="detail-title"><strong><?php echo $lang->project->acl;?></strong></div>
            <div class="detail-content">
              <p><?php echo $lang->project->aclList[$project->acl];?></p>
            </div>
          </div>
          <?php $this->printExtendFields($project, 'div', "position=right&inForm=0&inCell=1");?>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="mainActions" class='main-actions'>
  <nav class="container"></nav>
<?php include '../../common/view/footer.html.php';?>
