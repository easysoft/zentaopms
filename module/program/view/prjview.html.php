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
  <div class="col-12 main-col">
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
                <span class="label label-primary label-outline"><?php echo zget($lang->program->PRJLifeTimeList, $project->lifetime);?></span>
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
                    <th><?php echo $lang->project->totalEstimate;?></th>
                    <td><em><?php echo (float)$workhour->totalEstimate . $lang->hourCommon;?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->project->end;?></th>
                    <td><?php echo $project->end;?></td>
                    <th><?php echo $lang->project->totalConsumed;?></th>
                    <td><em><?php echo (float)$workhour->totalConsumed . $lang->hourCommon;?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->project->totalDays;?></th>
                    <td><?php echo $project->days;?></td>
                    <th><?php echo $lang->project->totalLeft;?></th>
                    <td><em><?php echo (float)$workhour->totalLeft . $lang->hourCommon;?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->project->totalHours;?></th>
                    <td><em><?php echo (float)$workhour->totalHours . $lang->hourCommon;?></em></td>
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
              <p><?php echo $lang->program->PGMPRJAclList[$project->acl];?></p>
            </div>
          </div>
          <?php $this->printExtendFields($project, 'div', "position=right&inForm=0&inCell=1");?>
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
        $params = "project=$project->id";
        common::printBack(inlink('prjbrowse'));
        if(!$project->deleted)
        {
            echo "<div class='divider'></div>";
            common::printIcon('program', 'PRJStart',    "projectID=$project->id", $project, 'button', 'play', '', 'iframe', true);
            common::printIcon('program', 'PRJActivate', "projectID=$project->id", $project, 'button', 'magic', '', 'iframe', true);
            common::printIcon('program', 'PRJSuspend',  "projectID=$project->id", $project, 'button', 'pause', '', 'iframe', true);
            common::printIcon('program', 'PRJClose',    "projectID=$project->id", $project, 'button', 'off', '', 'iframe', true);

            echo $this->buildOperateMenu($project, 'view');

            echo "<div class='divider'></div>";
            common::printIcon('program', 'PRJEdit', $params, $project, 'button', 'edit');
            common::printIcon('program', 'PRJDelete', $params, $project, 'button', 'trash', 'hiddenwin');
        }
        ?>
      </div>
    </div>
  </div>
</div>

<div id="mainActions" class='main-actions'>
  <nav class="container"></nav>
<?php include '../../common/view/footer.html.php';?>
