<?php
/**
 * The view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: view.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->productplan->confirmUnlinkStory)?>
<?php js::set('confirmUnlinkBug', $lang->productplan->confirmUnlinkBug)?>
<?php js::set('planID', $plan->id);?>
<?php js::set('storyPageID', $storyPager->pageID);?>
<?php js::set('storyRecPerPage', $storyPager->recPerPage);?>
<?php js::set('storyRecTotal', $storyPager->recTotal);?>
<?php js::set('storySummary', $summary);?>
<?php js::set('storyCommon', $lang->SRCommon);?>
<?php js::set('checkedSummary', str_replace('%storyCommon%', $lang->SRCommon, $lang->product->checkedSummary));?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $browseLink = $this->session->productPlanList ? $this->session->productPlanList : inlink('browse', "planID=$plan->product");?>
    <?php common::printBack($browseLink, 'btn btn-primary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $plan->id;?></span>
      <span title='<?php echo $plan->title;?>' class='text'><?php echo $plan->title;?></span>
      <span class='label label-info label-badge'>
        <?php echo ($plan->begin == $config->productplan->future || $plan->end == $config->productplan->future) ? $lang->productplan->future : $plan->begin . '~' . $plan->end;?>
      </span>
      <?php if($plan->deleted):?>
      <span class='label label-danger'><?php echo $lang->product->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
  <div class='btn-toolbar pull-right' id='actionsBox'>
    <?php if(!$plan->deleted && !isonlybody()) echo $this->productplan->buildOperateMenu($plan, 'view'); ?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class='tabs' id='tabsNav'>
    <?php if($this->app->getViewType() == 'xhtml'):?>
    <div class="plan-title"><?php echo $product->name . ' ' . $plan->title ?></div>
    <div class='tab-btn-container'>
    <?php endif;?>
    <ul class='nav nav-tabs'>
        <li class='<?php if($type == 'story') echo 'active'?>'>
          <a href='#stories' data-toggle='tab'>
            <?php echo  html::icon($lang->icons['story'], 'text-primary') . ' ' . $lang->productplan->linkedStories;?>
            <?php if($this->app->getViewType() == 'xhtml'):?>
            <span>(<?php echo $storyPager->recTotal;?>)</span>
            <?php endif;?>
          </a>
        </li>
        <li class='<?php if($type == 'bug') echo 'active'?>'>
          <a href='#bugs' data-toggle='tab'>
            <?php echo  html::icon($lang->icons['bug'], 'text-red') . ' ' . $lang->productplan->linkedBugs;?>
            <?php if($this->app->getViewType() == 'xhtml'):?>
            <span>(<?php echo $bugPager->recTotal;?>)</span>
            <?php endif;?>
          </a>
        </li>
        <li class='<?php if($type == 'planInfo') echo 'active'?>'>
          <a href='#planInfo' data-toggle='tab'><?php echo  html::icon($lang->icons['plan'], 'text-info') . ' ' . $lang->productplan->view;?></a>
        </li>
    </ul>
    <?php if($this->app->getViewType() == 'xhtml'):?>
    </div>
    <?php endif;?>
    <div class='tab-content'>
      <div id='stories' class='tab-pane <?php if($type == 'story') echo 'active'?>'>
        <?php $canOrder = common::hasPriv('execution', 'storySort');?>
        <div class='actions'>
          <?php if(!$plan->deleted and $plan->parent >= 0 and $canBeChanged):?>
          <div class="btn-group">
            <div class='drop-down dropdown-hover'>
              <?php
              $createLink = common::hasPriv('story', 'create') ? $this->createLink('story', 'create', "productID=$plan->product&branch=$plan->branch&moduleID=0&storyID=0&projectID=$projectID&bugID=0&planID=$plan->id") : '#';
              $createMisc = common::hasPriv('story', 'create') ? 'btn btn-secondary' : " btn btn-secondary disabled";
              echo html::a($createLink, "<i class='icon icon-plus'></i><span class='text'>" . $lang->story->create . "</span><span class='caret'>", '', "class='$createMisc' data-app={$app->tab}");
              ?>
              <ul class='dropdown-menu pull-right'>
                <?php $disabled = common::hasPriv('story', 'batchCreate') ? '' : "class='disabled'";?>
                <li <?php echo $disabled?>>
                  <?php
                  $batchLink = common::hasPriv('story', 'batchCreate') ? $this->createLink('story', 'batchCreate', "productID=$plan->product&branch=$plan->branch&moduleID=0&story=0&project=$projectID&plan={$plan->id}") : '#';
                  echo html::a($batchLink, "<span class='text'>" . $lang->story->batchCreate . '</span>', '', "class='btn btn-link' data-app={$app->tab}");
                  ?>
                </li>
              </ul>
            </div>
          </div>
          <?php endif;?>
          <?php if(common::hasPriv('productplan', 'linkStory', $plan) and $plan->parent >= 0) echo html::a("javascript:showLink($plan->id, \"story\")", '<i class="icon-link"></i> ' . $lang->productplan->linkStory, '', "class='btn btn-primary'");?>
        </div>
        <?php if(common::hasPriv('productplan', 'linkStory')):?>
        <div class='linkBox cell hidden'></div>
        <?php endif;?>
        <form class='main-table table-story<?php if($link === 'true' and $type == 'story') echo " hidden";?>' data-ride="" method='post' target='hiddenwin' action="<?php echo inlink('batchUnlinkStory', "planID=$plan->id&orderBy=$orderBy");?>">
          <table class='table has-sort-head' id='storyList'>
            <?php
            $canBatchUnlink       = common::hasPriv('productPlan', 'batchUnlinkStory');
            $canBatchClose        = common::hasPriv('story', 'batchClose');
            $canBatchEdit         = common::hasPriv('story', 'batchEdit');
            $canBatchReview       = common::hasPriv('story', 'batchReview');
            $canBatchChangeBranch = common::hasPriv('story', 'batchChangeBranch');
            $canBatchChangeModule = common::hasPriv('story', 'batchChangeModule');
            $canBatchChangePlan   = common::hasPriv('story', 'batchChangePlan');
            $canBatchChangeStage  = common::hasPriv('story', 'batchChangeStage');
            $canBatchAssignTo     = common::hasPriv('story', 'batchAssignTo');

            $canBatchAction = ($canBeChanged and ($canBatchUnlink or $canBatchClose or $canBatchEdit or $canBatchReview or $canBatchChangeBranch or $canBatchChangeModule or $canBatchChangePlan or $canBatchChangeStage or $canBatchAssignTo));
            $vars = "planID={$plan->id}&type=story&orderBy=%s&link=$link&param=$param";
            ?>
            <thead>
              <tr class='text-center'>
                <?php if($this->app->getViewType() == 'xhtml'):?>
                <th class='c-id text-left'>
                  <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                </th>
                <th class='text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->story->title);?></th>
                <th class='w-70px' title='<?php echo $lang->pri;?>'> <?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
                <th class='w-70px'> <?php common::printOrderLink('status', $orderBy, $vars, $lang->statusAB);?></th>
                <?php else:?>
                <th class='c-id text-left'>
                  <?php if($planStories && $canBatchAction):?>
                  <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                    <label></label>
                  </div>
                  <?php endif;?>
                  <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                </th>
                <?php if($canOrder):?>
                <th class='w-90px'><?php echo $lang->productplan->updateOrder;?></th>
                <?php endif;?>
                <th class='text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->story->title);?></th>
                <th class='w-90px text-left'><?php common::printOrderLink('module', $orderBy, $vars, $lang->story->module);?></th>
                <th class='w-70px' title='<?php echo $lang->pri;?>'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
                <th class='w-70px'><?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
                <th class='c-user'><?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
                <th class='c-user'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
                <th class='w-110px text-right'><?php common::printOrderLink('estimate', $orderBy, $vars, $lang->story->estimateAB);?></th>
                <th class='w-80px'><?php common::printOrderLink('stage', $orderBy, $vars, $lang->story->stageAB);?></th>
                <th class='c-actions-1 w-90px'><?php echo $lang->actions?></th>
                <?php endif;?>
              </tr>
            </thead>
            <tbody class='sortable text-center'>
              <?php
              $totalEstimate = 0.0;
              ?>
              <?php foreach($planStories as $story):?>
              <?php
              $viewLink = $this->createLink('story', 'view', "storyID=$story->id");
              $totalEstimate += $story->estimate;
              ?>
              <tr data-id='<?php echo $story->id;?>' data-estimate='<?php echo $story->estimate?>' <?php if(!empty($story->children)) echo "data-children=" . count($story->children);?> data-cases='<?php echo zget($storyCases, $story->id, 0);?>'>
                <?php if($this->app->getViewType() == 'xhtml'):?>
                <td class='c-id text-left'>
                <?php printf('%03d', $story->id);?>
                </td>
                <td class='text-left nobr' title='<?php echo $story->title?>'>
                  <?php
                  if($story->parent > 0) echo "<span class='label label-badge label-light' title={$lang->story->children}>{$lang->story->childrenAB}</span>";
                  echo $story->title;
                  ?>
                </td>
                <td><span class="<?php echo $story->pri ? 'label-pri label-pri-' . $story->pri : "";?>" title='<?php echo zget($lang->story->priList, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri);?></span></td>
                <td>
                  <span class='status-story status-<?php echo $story->status?>'>
                    <?php echo $this->processStatus('story', $story);?>
                  </span>
                </td>
                <?php else:?>
                <td class='c-id text-left'>
                  <?php if($canBatchAction):?>
                  <?php echo html::checkbox('storyIdList', array($story->id => sprintf('%03d', $story->id)));?>
                  <?php else:?>
                  <?php printf('%03d', $story->id);?>
                  <?php endif;?>
                </td>
                <?php if($canOrder):?><td class='sort-handler'><i class='icon-move'></i></td><?php endif;?>
                <td class='text-left nobr' title='<?php echo $story->title?>'>
                  <?php
                  if($story->parent > 0) echo "<span class='label label-badge label-light' title={$lang->story->children}>{$lang->story->childrenAB}</span>";
                  echo html::a($viewLink , $story->title, '', "style='color: $story->color' data-app={$this->app->tab}");
                  ?>
                </td>
                <td class='text-left nobr' title='<?php echo zget($modulePairs, $story->module, '');?>'><?php echo zget($modulePairs, $story->module, '');?></td>
                <td><span class="<?php echo $story->pri ? 'label-pri label-pri-' . $story->pri : "";?>" title='<?php echo zget($lang->story->priList, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri);?></span></td>
                <td>
                  <span class='status-story status-<?php echo $story->status?>'><?php echo $this->processStatus('story', $story);?></span>
                </td>
                <td><?php echo zget($users, $story->openedBy);?></td>
                <td><?php echo zget($users, $story->assignedTo);?></td>
                <td class='text-right' title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
                <td><?php echo $lang->story->stageList[$story->stage];?></td>
                <td class='c-actions'>
                  <?php
                  if($canBeChanged and common::hasPriv('productplan', 'unlinkStory'))
                  {
                      $unlinkURL = $this->createLink('productplan', 'unlinkStory', "story=$story->id&plan=$plan->id");
                      echo html::a($unlinkURL, '<i class="icon-unlink"></i>', 'hiddenwin', "class='btn' title='{$lang->productplan->unlinkStory}'");
                  }
                  ?>
                </td>
                <?php endif;?>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <?php if($planStories):?>
          <div class='table-footer'>
            <?php if($canBatchAction and $this->app->getViewType() != 'xhtml'):?>
            <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
            <div class='table-actions btn-toolbar'>
              <?php $actionLink = inlink('batchUnlinkStory', "planID=$plan->id&orderBy=$orderBy");?>
              <div class='btn-group dropup'>
                <?php echo html::commonButton($lang->productplan->unlinkStoryAB, ($canBatchUnlink ? '' : 'disabled') . "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"");?>
                <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
                <ul class='dropdown-menu'>
                  <?php
                  $class = "class='disabled'";

                  $actionLink = $this->createLink('story', 'batchClose', "productID=$plan->product");
                  $misc = $canBatchClose ? "onclick=\"setFormAction('$actionLink', '', this)\"" : $class;
                  echo "<li>" . html::a('#', $lang->close, '', $misc) . "</li>";

                  $actionLink = $this->createLink('story', 'batchEdit', "productID=$plan->product&projectID=$projectID&branch=$branch");
                  $misc = $canBatchEdit ? "onclick=\"setFormAction('$actionLink', '', this)\"" : $class;
                  echo "<li>" . html::a('#', $lang->edit, '', $misc) . "</li>";

                  if($canBatchReview)
                  {
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->story->review, '', "id='reviewItem'");
                      echo "<ul class='dropdown-menu'>";
                      unset($lang->story->reviewResultList['']);
                      unset($lang->story->reviewResultList['revert']);
                      foreach($lang->story->reviewResultList as $key => $result)
                      {
                          $actionLink = $this->createLink('story', 'batchReview', "result=$key");
                          if($key == 'reject')
                          {
                              echo "<li class='dropdown-submenu'>";
                              echo html::a('#', $result, '', "id='rejectItem'");
                              echo "<ul class='dropdown-menu'>";
                              unset($lang->story->reasonList['']);
                              unset($lang->story->reasonList['subdivided']);
                              unset($lang->story->reasonList['duplicate']);

                              foreach($lang->story->reasonList as $key => $reason)
                              {
                                  $actionLink = $this->createLink('story', 'batchReview', "result=reject&reason=$key");
                                  echo "<li>";
                                  echo html::a('#', $reason, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"");
                                  echo "</li>";
                              }
                              echo '</ul></li>';
                          }
                          else
                          {
                            echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"") . '</li>';
                          }
                      }
                      echo '</ul></li>';
                  }
                  else
                  {
                      echo '<li>' . html::a('javascript:;', $lang->story->review,  '', $class) . '</li>';
                  }

                  if($canBatchChangeBranch and $this->session->currentProductType != 'normal')
                  {
                      $withSearch = count($branchTagOption) > 8;
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->product->branchName[$this->session->currentProductType], '', "id='branchItem'");
                      echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                      echo '<ul class="dropdown-list">';
                      foreach($branchTagOption as $branchID => $branchName)
                      {
                          $actionLink = $this->createLink('story', 'batchChangeBranch', "branchID=$branchID");
                          echo "<li class='option' data-key='$branchID'>" . html::a('#', $branchName, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"") . "</li>";
                      }
                      echo '</ul>';
                      if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                      echo '</div></li>';
                  }

                  if($canBatchChangeModule)
                  {
                      $withSearch = count($modules) > 8;
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->story->moduleAB, '', "id='moduleItem'");
                      echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                      echo '<ul class="dropdown-list">';
                      foreach($modules as $moduleId => $module)
                      {
                          $actionLink = $this->createLink('story', 'batchChangeModule', "moduleID=$moduleId");
                          echo "<li class='option' data-key='$moduleId'>" . html::a('#', $module, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"") . "</li>";
                      }
                      echo '</ul>';
                      if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                      echo '</div></li>';
                  }
                  else
                  {
                      echo '<li>' . html::a('javascript:;', $lang->story->moduleAB, '', $class) . '</li>';
                  }

                  if($canBatchChangePlan)
                  {
                      unset($plans['']);
                      unset($plans[$plan->id]);
                      $plans      = array(0 => $lang->null) + $plans;
                      $withSearch = count($plans) > 8;
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->story->planAB, '', "id='planItem'");
                      echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                      echo '<ul class="dropdown-list">';
                      foreach($plans as $planID => $planName)
                      {
                          $actionLink = $this->createLink('story', 'batchChangePlan', "planID=$planID&oldPlanID=$plan->id");
                          echo "<li class='option' data-key='$planID'>" . html::a('#', $planName, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"") . "</li>";
                      }
                      echo '</ul>';
                      if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                      echo '</div></li>';
                  }
                  else
                  {
                      echo '<li>' . html::a('javascript:;', $lang->story->planAB, '', $class) . '</li>';
                  }

                  if($canBatchChangeStage)
                  {
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->story->stageAB, '', "id='stageItem'");
                      echo "<ul class='dropdown-menu'>";
                      $lang->story->stageList[''] = $lang->null;
                      foreach($lang->story->stageList as $key => $stage)
                      {
                          $actionLink = $this->createLink('story', 'batchChangeStage', "stage=$key");
                          echo "<li>" . html::a('#', $stage, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"") . "</li>";
                      }
                      echo '</ul></li>';
                  }
                  else
                  {
                      echo '<li>' . html::a('javascript:;', $lang->story->stageAB, '', $class) . '</li>';
                  }

                  if($canBatchAssignTo)
                  {
                        $withSearch = count($users) > 10;
                        $actionLink = $this->createLink('story', 'batchAssignTo', "productID=$plan->product");
                        echo html::select('assignedTo', $users, '', 'class="hidden"');
                        echo "<li class='dropdown-submenu'>";
                        echo html::a('javascript::', $lang->story->assignedTo, '', 'id="assignItem"');
                        echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                        echo '<ul class="dropdown-list">';
                        foreach ($users as $key => $value)
                        {
                            if(empty($key) or $key == 'closed') continue;
                            echo "<li class='option' data-key='$key'>" . html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", false, \"#storyList\")", $value, '', '') . '</li>';
                        }
                        echo "</ul>";
                        if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                        echo "</div></li>";
                  }
                  else
                  {
                      echo '<li>' . html::a('javascript:;', $lang->story->assignedTo, '', $class) . '</li>';
                  }
                  ?>
                </ul>
              </div>
            </div>
            <?php endif;?>
            <?php if($this->app->getViewType() != 'xhtml'):?>
            <div class='table-statistic'><?php echo $summary;?></div>
            <?php endif;?>
            <?php
            $this->app->rawParams['type'] = 'story';
            $storyPager->show('right', 'pagerjs');
            $this->app->rawParams['type'] = $type;
            ?>
          </div>
          <?php endif;?>
        </form>
      </div>
      <div id='bugs' class='tab-pane <?php if($type == 'bug') echo 'active';?>'>
        <?php if(common::hasPriv('productplan', 'linkBug', $plan) and $plan->parent >= 0):?>
        <div class='actions'>
        <?php echo html::a("javascript:showLink($plan->id, \"bug\")", '<i class="icon-bug"></i> ' . $lang->productplan->linkBug, '', "class='btn btn-primary'");?>
        </div>
        <div class='linkBox cell hidden'></div>
        <?php endif;?>
        <form class='main-table table-bug<?php if($link === 'true' and $type == 'bug') echo " hidden";?>' data-ride='table' method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug', "planID=$plan->id&orderBy=$orderBy");?>">
          <table class='table has-sort-head' id='bugList'>
            <?php
            $canBatchUnlink     = common::hasPriv('productplan', 'batchUnlinkBug');
            $canBatchEdit       = common::hasPriv('bug', 'batchEdit');
            $canBatchChangePlan = common::hasPriv('bug', 'batchChangePlan');
            $canBatchAction     = $canBeChanged and ($canBatchUnlink or $canBatchEdit or $canBatchChangePlan);
            ?>
            <?php $vars = "planID={$plan->id}&type=bug&orderBy=%s&link=$link&param=$param"; ?>
            <thead>
              <tr class='text-center'>
                <?php if($this->app->getViewType() == 'xhtml'):?>
                <th class='c-id text-left'>
                  <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                </th>
                <th class='text-left'><?php common::printOrderLink('title',    $orderBy, $vars, $lang->bug->title);?></th>
                <th class='w-70px' title='<?php echo $lang->pri;?>'> <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
                <th class='w-100px'><?php common::printOrderLink('status',     $orderBy, $vars, $lang->bug->status);?></th>
                <?php else:?>
                <th class='c-id text-left'>
                  <?php if($planBugs && $canBatchUnlink):?>
                  <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                    <label></label>
                  </div>
                  <?php endif;?>
                  <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                </th>
                <th class='text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->bug->title);?></th>
                <th class='c-pri' title='<?php echo $lang->pri;?>'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
                <th class='c-status'><?php common::printOrderLink('status',    $orderBy, $vars, $lang->bug->status);?></th>
                <th class='c-user'> <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
                <th class='c-user'> <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->bug->abbr->assignedTo);?></th>
                <th class='c-actions'> <?php echo $lang->actions?></th>
                <?php endif;?>
              </tr>
            </thead>
            <tbody class='text-center'>
              <?php foreach($planBugs as $bug):?>
              <tr>
                <?php if($this->app->getViewType() == 'xhtml'):?>
                <td class='c-id text-left'>
                  <?php printf('%03d', $bug->id);?>
                </td>
                <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo $bug->title?></td>
                <td><span class='<?php echo $bug->pri ? "label-pri label-pri-{$bug->pri}" : "";?>' title='<?php echo zget($lang->bug->priList, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri);?></span></td>
                <td>
                  <span class='status-bug status-<?php echo $bug->status?>'><?php echo $this->processStatus('bug', $bug);?></span>
                </td>
                <?php else:?>
                <td class='c-id text-left'>
                  <?php if($canBatchUnlink):?>
                  <?php echo html::checkbox('bugIDList', array($bug->id => sprintf('%03d', $bug->id)));?>
                  <?php else:?>
                  <?php printf('%03d', $bug->id);?>
                  <?php endif;?>
                </td>
                <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, '', "data-app={$this->app->tab}");?></td>
                <td><span class='<?php echo $bug->pri ? "label-pri label-pri-{$bug->pri}" : "";?>' title='<?php echo zget($lang->bug->priList, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri);?></span></td>
                <td>
                  <span class='status-bug status-<?php echo $bug->status?>'><?php echo $this->processStatus('bug', $bug);?></span>
                </td>
                <td><?php echo zget($users, $bug->openedBy);?></td>
                <td><?php echo zget($users, $bug->assignedTo);?></td>
                <td class='c-actions'>
                  <?php
                  if($canBeChanged and common::hasPriv('productplan', 'unlinkBug'))
                  {
                      $unlinkURL = $this->createLink('productplan', 'unlinkBug', "bugID=$bug->id&planID=$plan->id");
                      echo html::a($unlinkURL, '<i class="icon-unlink"></i>', 'hiddenwin', "class='btn' title='{$lang->productplan->unlinkBug}'");
                  }
                  ?>
                </td>
                <?php endif;?>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <?php if($planBugs):?>
          <div class='table-footer'>
            <?php if($canBatchAction and $this->app->getViewType() != 'xhtml'):?>
            <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
            <div class="table-actions btn-toolbar">
              <div class='btn-group dropup'>
                <?php $actionLink = inlink('batchUnlinkbug', "planID=$plan->id&orderBy=$orderBy");?>
                <?php echo html::commonButton($lang->productplan->unlinkAB, ($canBatchUnlink ? '' : 'disabled') . "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"");?>
                <?php if($canBatchChangePlan || $canBatchEdit):?>
                <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
                <ul class='dropdown-menu'>
                  <?php
                  $class = "class='disabled'";
                  $actionLink = $this->createLink('bug', 'batchEdit', "productID=$plan->product&branch=$branch");
                  $misc       = $canBatchEdit ? "onclick=\"setFormAction('$actionLink', '', this)\"" : $class;
                  if($canBatchEdit) echo "<li>" . html::a('#', $lang->edit, '', $misc) . "</li>";

                  if($canBatchChangePlan)
                  {
                      unset($plans['']);
                      unset($plans[$plan->id]);
                      $plans      = array(0 => $lang->null) + $plans;
                      $withSearch = count($plans) > 8;
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->productplan->plan, '', "id='planItem'");
                      echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                      echo '<ul class="dropdown-list">';
                      foreach($plans as $planID => $planName)
                      {
                          $actionLink = $this->createLink('bug', 'batchChangePlan', "planID=$planID");
                          echo "<li class='option' data-key='$planID'>" . html::a('#', $planName, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"") . "</li>";
                      }
                      echo '</ul>';
                      if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                      echo '</div></li>';
                  }
                  ?>
                </ul>
                <?php endif;?>
              </div>
            </div>
            <?php endif;?>
            <?php if($this->app->getViewType() != 'xhtml'):?>
            <div class='table-statistic'><?php echo sprintf($lang->productplan->bugSummary, count($planBugs));?></div>
            <?php endif;?>
            <?php
            $this->app->rawParams['type'] = 'bug';
            $bugPager->show('right', 'pagerjs');
            $this->app->rawParams['type'] = $type;
            ?>
          </div>
          <?php endif;?>
        </form>
      </div>
      <div id='planInfo' class='tab-pane <?php if($type == 'planInfo') echo 'active';?>'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->productplan->basicInfo;?></div>
            <div class='detail-content'>
              <table class='table table-data table-condensed table-borderless'>
                <tr>
                  <th class='w-80px strong'><?php echo $lang->productplan->title;?></th>
                  <td><?php echo $plan->title;?></td>
                </tr>
                <?php if($plan->parent > 0):?>
                <tr>
                  <th><?php echo $lang->productplan->parent;?></th>
                  <td><?php echo html::a(inlink('view', "planID={$parentPlan->id}"), "#{$parentPlan->id} " . $parentPlan->title, '', "data-app={$app->tab}");?></td>
                </tr>
                <?php endif;?>
                <?php if($product->type != 'normal'):?>
                <tr>
                  <th><?php echo $lang->product->branch;?></th>
                  <?php
                  $branches = '';
                  foreach(explode(',', $branch) as $branchID) $branches .= "{$branchOption[$branchID]},";
                  ?>
                  <td><?php echo trim($branches, ',');?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->productplan->begin;?></th>
                  <td><?php echo $plan->begin == $config->productplan->future ? $lang->productplan->future : $plan->begin;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->productplan->end;?></th>
                  <td><?php echo $plan->end == $config->productplan->future ? $lang->productplan->future : $plan->end;?></td>
                </tr>
                <?php if($plan->parent == '-1'):?>
                <tr>
                  <th><?php echo $lang->productplan->children;?></th>
                  <td>
                    <?php foreach($childrenPlans as $childrenPlan):?>
                    <?php echo html::a(inlink('view', "planID={$childrenPlan->id}"), "#{$childrenPlan->id} " . $childrenPlan->title, '', "data-app={$app->tab}") . '<br />';?>
                    <?php endforeach;?>
                  </td>
                </tr>
                <?php endif;?>
                <?php $this->printExtendFields($plan, 'table', 'inForm=0');?>
                <tr>
                  <th><?php echo $lang->productplan->status;?></th>
                  <td><?php echo $lang->productplan->statusList[$plan->status];?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->productplan->desc;?></th>
                  <td><?php echo $plan->desc;?></td>
                </tr>
              </table>
            </div>
          </div>
          <?php if($this->app->getViewType() != 'xhtml') include '../../common/view/action.html.php';?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php js::set('param', helper::safe64Decode($param))?>
<?php js::set('link', $link)?>
<?php js::set('planID', $plan->id)?>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('type', $type)?>
<?php if($this->app->getViewType() == 'xhtml'):?>
<script>
$(function()
{
    function handleClientReady()
    {
        if(!window.adjustXXCViewHeight) return;
        window.adjustXXCViewHeight(null, true);
        $('#mainContent').on('show.zui.tab', function(){window.adjustXXCViewHeight(null, true);});
    }
    if(window.xuanReady) handleClientReady();
    else $(window).on('xuan-ready', handleClientReady);
});
</script>
<?php endif; ?>
<?php include '../../common/view/footer.html.php';?>
