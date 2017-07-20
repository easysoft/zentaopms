<?php
/**
 * The view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: view.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->productplan->confirmUnlinkStory)?>
<?php js::set('confirmUnlinkBug', $lang->productplan->confirmUnlinkBug)?>
<div id='titlebar'>
  <div class='heading'>
  <span class='prefix'><?php echo html::icon($lang->icons['plan']);?> <strong><?php echo $plan->id;?></strong></span>
    <strong><?php echo $plan->title;?></strong>
    <?php if($product->type !== 'normal') echo "<span title='{$lang->product->branchName[$product->type]}' class='label label-branch label-badge'>" . $branches[$branch] . '</span>';?>
    <span class='label label-info label-badge'><?php echo $plan->begin . '~' . $plan->end;?></span>
    <?php if($plan->deleted):?>
    <span class='label label-danger'><?php echo $lang->plan->deleted;?></span>
    <?php endif; ?>
  </div>
  <div class='actions'>
  <?php
   $browseLink = $this->session->productPlanList ? $this->session->productPlanList : inlink('browse', "planID=$plan->id");
   if(!$plan->deleted)
   {
      ob_start();
      echo "<div class='btn-group'>";
      common::printIcon('story', 'create', "productID=$plan->product&branch=$plan->branch&moduleID=0&storyID=0&projectID=0&bugID=0&planID=$plan->id", '', 'button', 'plus');
      if(common::hasPriv('productplan', 'linkStory')) echo html::a(inlink('view', "planID=$plan->id&type=story&orderBy=id_desc&link=true"), '<i class="icon-link"></i> ' . $lang->productplan->linkStory, '', "class='btn'");
      if(common::hasPriv('productplan', 'linkBug') and $config->global->flow != 'onlyStory') echo html::a(inlink('view', "planID=$plan->id&type=bug&orderBy=id_desc&link=true"), '<i class="icon-bug"></i> ' . $lang->productplan->linkBug, '', "class='btn'");
      echo '</div>';
      echo "<div class='btn-group'>";
      common::printIcon('productplan', 'edit',     "planID=$plan->id");
      common::printIcon('productplan', 'delete',   "planID=$plan->id", '', 'button', '', 'hiddenwin');
      echo '</div>';
      $actionLinks = ob_get_contents();
      ob_end_clean();
      echo $actionLinks;
   }
   common::printRPN($browseLink);
  ?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='<?php if($type == 'story') echo 'active'?>'><a href='#stories' data-toggle='tab'><?php echo  html::icon($lang->icons['story']) . ' ' . $lang->productplan->linkedStories;?></a></li>
          <?php if($config->global->flow != 'onlyStory'):?>
          <li class='<?php if($type == 'bug') echo 'active'?>'><a href='#bugs' data-toggle='tab'><?php echo  html::icon($lang->icons['bug']) . ' ' . $lang->productplan->linkedBugs;?></a></li>
          <?php endif;?>
          <li><a href='#planInfo' data-toggle='tab'><?php echo  html::icon($lang->icons['plan']) . ' ' . $lang->productplan->view;?></a></li>
        </ul>
        <div class='tab-content'>
          <div id='stories' class='tab-pane <?php if($type == 'story') echo 'active'?>'>
            <?php if(common::hasPriv('productplan', 'linkStory')):?>
            <div class='action'>
            <?php echo html::a("javascript:showLink($plan->id, \"story\")", '<i class="icon-link"></i> ' . $lang->productplan->linkStory, '', "class='btn btn-sm btn-primary'");?>
            </div>
            <div class='linkBox'></div>
            <?php endif;?>
            <form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inlink('batchUnlinkStory', "planID=$plan->id&orderBy=$orderBy");?>">
              <table class='table tablesorter table-condensed table-hover table-striped table-fixed table-selectable' id='storyList'>
                <?php $vars = "planID={$plan->id}&type=story&orderBy=%s&link=$link&param=$param"; ?>
                <thead>
                <tr>
                  <th class='w-id {sorter:false}' >   <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
                  <th class='w-pri {sorter:false}'>   <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
                  <th class='{sorter:false}'>         <?php common::printOrderLink('title',      $orderBy, $vars, $lang->story->title);?></th>
                  <th class='w-user {sorter:false}'>  <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
                  <th class='w-user {sorter:false}'>  <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
                  <th class='w-60px {sorter:false}'>  <?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
                  <th class='w-status {sorter:false}'><?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
                  <th class='w-80px {sorter:false}'>  <?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
                  <th class='w-50px {sorter:false}'>  <?php echo $lang->actions?></th>
                </tr>
                </thead>
                <tbody>
                  <?php
                  $totalEstimate = 0.0;
                  $canBatchUnlink     = common::hasPriv('productPlan', 'batchUnlinkStory');
                  $canBatchChangePlan = common::hasPriv('story', 'batchChangePlan');
                  ?>
                  <?php foreach($planStories as $story):?>
                  <?php
                  $viewLink = $this->createLink('story', 'view', "storyID=$story->id");
                  $totalEstimate += $story->estimate;
                  ?>
                  <tr class='text-center'>
                    <td class='cell-id'>
                      <?php if($canBatchUnlink or $canBatchChangePlan):?>
                      <input type='checkbox' name='storyIDList[]'  value='<?php echo $story->id;?>'/> 
                      <?php endif;?>
                      <?php echo html::a($viewLink, sprintf("%03d", $story->id));?>
                    </td>
                    <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
                    <td class='text-left nobr' title='<?php echo $story->title?>'>
                      <?php if($modulePairs and $story->module) echo "<span title='{$lang->story->module}' class='label label-info label-badge'>{$modulePairs[$story->module]}</span> "?>
                      <?php echo html::a($viewLink , $story->title);?>
                    </td>
                    <td><?php echo zget($users, $story->openedBy);?></td>
                    <td><?php echo zget($users, $story->assignedTo);?></td>
                    <td><?php echo $story->estimate;?></td>
                    <td class='story-<?php echo $story->status?>'><?php echo $lang->story->statusList[$story->status];?></td>
                    <td><?php echo $lang->story->stageList[$story->stage];?></td>
                    <td>
                      <?php
                      if(common::hasPriv('productplan', 'unlinkStory'))
                      {
                          $unlinkURL = $this->createLink('productplan', 'unlinkStory', "story=$story->id&plan=$plan->id&confirm=yes");
                          echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '<i class="icon-unlink"></i>', '', "class='btn-icon' title='{$lang->productplan->unlinkStory}'");
                      }
                      ?>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                  <td colspan='9'>
                    <div class='table-actions clearfix'>
                      <?php if(count($planStories)):?>
                      <?php echo html::selectButton();?>
                      <?php
                      $disabled   = $canBatchUnlink ? '' : "disabled='disabled'";
                      $actionLink = inlink('batchUnlinkStory', "planID=$plan->id&orderBy=$orderBy");
                      ?>
                      <div class='btn-group dropup'>
                        <?php echo html::commonButton($lang->productplan->unlinkStory, ($canBatchUnlink ? '' : 'disabled') . "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"");?>
                        <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
                        <ul class='dropdown-menu'>
                          <?php
                          $class = "class='disabled'";

                          $canBatchClose = common::hasPriv('story', 'batchClose');
                          $actionLink    = $this->createLink('story', 'batchClose', "productID=$plan->product");
                          $misc = $canBatchClose ? "onclick=\"setFormAction('$actionLink', '', this)\"" : $class;
                          echo "<li>" . html::a('#', $lang->close, '', $misc) . "</li>";

                          $canBatchEdit = common::hasPriv('story', 'batchEdit');
                          $actionLink   = $this->createLink('story', 'batchEdit', "productID=$plan->product&projectID=0&branch=$branch");
                          $misc = $canBatchEdit ? "onclick=\"setFormAction('$actionLink', '', this)\"" : $class;
                          echo "<li>" . html::a('#', $lang->edit, '', $misc) . "</li>";

                          if(common::hasPriv('story', 'batchReview'))
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
                                          echo html::a('#', $reason, '', "onclick=\"setFormAction('$actionLink','hiddenwin', this)\"");
                                          echo "</li>";
                                      }
                                      echo '</ul></li>';
                                  }
                                  else
                                  {
                                    echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink','hiddenwin', this)\"") . '</li>';
                                  }
                              }
                              echo '</ul></li>';
                          }
                          else
                          {
                              echo '<li>' . html::a('javascript:;', $lang->story->review,  '', $class) . '</li>';
                          }

                          if(common::hasPriv('story', 'batchChangeBranch') and $this->session->currentProductType != 'normal')
                          {
                              $withSearch = count($branches) > 8;
                              echo "<li class='dropdown-submenu'>";
                              echo html::a('javascript:;', $lang->product->branchName[$this->session->currentProductType], '', "id='branchItem'");
                              echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                              echo '<ul class="dropdown-list">';
                              foreach($branches as $branchID => $branchName)
                              {
                                  $actionLink = $this->createLink('story', 'batchChangeBranch', "branchID=$branchID");
                                  echo "<li class='option' data-key='$branchID'>" . html::a('#', $branchName, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"") . "</li>";
                              }
                              echo '</ul>';
                              if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                              echo '</div></li>';
                          }

                          if(common::hasPriv('story', 'batchChangeModule'))
                          {
                              $withSearch = count($modules) > 8;
                              echo "<li class='dropdown-submenu'>";
                              echo html::a('javascript:;', $lang->story->moduleAB, '', "id='moduleItem'");
                              echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                              echo '<ul class="dropdown-list">';
                              foreach($modules as $moduleId => $module)
                              {
                                  $actionLink = $this->createLink('story', 'batchChangeModule', "moduleID=$moduleId");
                                  echo "<li class='option' data-key='$moduleId'>" . html::a('#', $module, '', "onclick=\"setFormAction('$actionLink','hiddenwin', this)\"") . "</li>";
                              }
                              echo '</ul>';
                              if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                              echo '</div></li>';
                          }
                          else
                          {
                              echo '<li>' . html::a('javascript:;', $lang->story->moduleAB, '', $class) . '</li>';
                          }

                          if(common::hasPriv('story', 'batchChangePlan'))
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
                                  echo "<li class='option' data-key='$planID'>" . html::a('#', $planName, '', "onclick=\"setFormAction('$actionLink','hiddenwin', this)\"") . "</li>";
                              }
                              echo '</ul>';
                              if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                              echo '</div></li>';
                          }
                          else
                          {
                              echo '<li>' . html::a('javascript:;', $lang->story->planAB, '', $class) . '</li>';
                          }

                          if(common::hasPriv('story', 'batchChangeStage'))
                          {
                              echo "<li class='dropdown-submenu'>";
                              echo html::a('javascript:;', $lang->story->stageAB, '', "id='stageItem'");
                              echo "<ul class='dropdown-menu'>";
                              $lang->story->stageList[''] = $lang->null;
                              foreach($lang->story->stageList as $key => $stage)
                              {
                                  $actionLink = $this->createLink('story', 'batchChangeStage', "stage=$key");
                                  echo "<li>" . html::a('#', $stage, '', "onclick=\"setFormAction('$actionLink','hiddenwin', this)\"") . "</li>";
                              }
                              echo '</ul></li>';
                          }
                          else
                          {
                              echo '<li>' . html::a('javascript:;', $lang->story->stageAB, '', $class) . '</li>';
                          }

                          if(common::hasPriv('story', 'batchAssignTo'))
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
                                    echo "<li class='option' data-key='$key'>" . html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\")", $value, '', '') . '</li>';
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
                      <?php endif;?>
                      <div class='text'><?php echo $summary;?></div>
                    </div>
                  </td>
                </tr>
                </tfoot>
              </table>
            </form>
          </div>
          <div id='bugs' class='tab-pane <?php if($type == 'bug') echo 'active';?>'>
            <?php if(common::hasPriv('productplan', 'linkBug')):?>
            <div class='action'>
            <?php echo html::a("javascript:showLink($plan->id, \"bug\")", '<i class="icon-bug"></i> ' . $lang->productplan->linkBug, '', "class='btn btn-sm btn-primary'");?>
            </div>
            <div class='linkBox'></div>
            <?php endif;?>
            <form method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug', "planID=$plan->id&orderBy=$orderBy");?>">
              <table class='table tablesorter table-condensed table-hover table-striped table-fixed table-selectable' id='bugList'>
                <?php $vars = "planID={$plan->id}&type=bug&orderBy=%s&link=$link&param=$param"; ?>
                <thead>
                <tr>
                  <th class='w-id {sorter:false}'>    <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
                  <th class='w-pri {sorter:false}'>   <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
                  <th class='{sorter:false}'>         <?php common::printOrderLink('title',      $orderBy, $vars, $lang->bug->title);?></th>
                  <th class='w-user {sorter:false}'>  <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
                  <th class='w-user {sorter:false}'>  <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
                  <th class='w-status {sorter:false}'><?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
                  <th class='w-50px {sorter:false}'>  <?php echo $lang->actions?></th>
                </tr>
                </thead>
                <tbody>
                  <?php $canBatchUnlink = common::hasPriv('productPlan', 'batchUnlinkBug');?>
                  <?php foreach($planBugs as $bug):?>
                  <tr class='text-center'>
                    <td class='cell-id'>
                      <?php if($canBatchUnlink):?>
                      <input type='checkbox' name='unlinkBugs[]'  value='<?php echo $bug->id;?>'/> 
                      <?php endif;?>
                      <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), sprintf("%03d", $bug->id));?>
                    </td>
                    <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug->pri, $bug->pri)?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri);?></span></td>
                    <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
                    <td><?php echo zget($users, $bug->openedBy);?></td>
                    <td><?php echo zget($users, $bug->assignedTo);?></td>
                    <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status];?></td>
                    <td>
                      <?php
                      if(common::hasPriv('productplan', 'unlinkBug'))
                      {
                          $unlinkURL = $this->createLink('productplan', 'unlinkBug', "story=$bug->id&confirm=yes");
                          echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"bugList\",confirmUnlinkBug)", '<i class="icon-unlink"></i>', '', "class='btn-icon' title='{$lang->productplan->unlinkBug}'");
                      }
                      ?>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                  <td colspan='7'>
                    <div class='table-actions clearfix'>
                      <?php 
                      if(count($planBugs) and $canBatchUnlink)
                      {
                          echo html::selectButton();
                          echo html::submitButton($lang->productplan->batchUnlink);
                      }
                      ?>
                      <div class='text'><?php echo sprintf($lang->productplan->bugSummary, count($planBugs));?> </div>
                    </div>
                  </td>
                </tr>
                </tfoot>
              </table>
            </form>
          </div>
          <div id='planInfo' class='tab-pane'>
            <div>
              <fieldset>
                <legend><?php echo $lang->productplan->basicInfo?></legend>
                <table class='table table-data table-condensed table-borderless'>
                  <tr>
                    <th class='w-80px strong'><?php echo $lang->productplan->title;?></th> 
                    <td><?php echo $plan->title;?></td>
                  </tr>
                  <?php if($product->type != 'normal'):?>
                  <tr>
                    <th><?php echo $lang->product->branch;?></th>
                    <td><?php echo $branches[$plan->branch];?></td>
                  </tr>
                  <?php endif;?>
                  <tr>
                    <th><?php echo $lang->productplan->begin;?></th>
                    <td><?php echo $plan->begin;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->productplan->end;?></th>
                    <td><?php echo $plan->end;?></td>
                  </tr>
                </table>
              </fieldset>
              <fieldset>
                <legend><?php echo $lang->productplan->desc;?></legend>
                <div class='article-content'><?php echo $plan->desc;?></div>
              </fieldset>
              <?php include '../../common/view/action.html.php';?>
            </div>
          </div>
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
<?php include '../../common/view/footer.html.php';?>
