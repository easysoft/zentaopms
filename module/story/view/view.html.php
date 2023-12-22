<?php
/**
 * The view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: view.html.php 4952 2013-07-02 01:14:58Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../ai/view/promptmenu.html.php';?>
<?php $browseLink = $app->session->storyList ? $app->session->storyList : $this->createLink('product', 'browse', "productID=$story->product");?>
<?php js::set('sysurl', common::getSysUrl());?>
<?php js::set('storyType', $story->type);?>
<?php js::set('page', $this->app->rawMethod);?>
<?php if(strpos($_SERVER["QUERY_STRING"], 'isNotice=1') === false):?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $story->id?></span>
      <span class="text" title='<?php echo $story->title;?>' style='color: <?php echo $story->color;?>'>
        <?php if($story->parent > 0) echo '<span class="label label-badge label-primary no-margin">' . $this->lang->story->childrenAB . '</span>';?>
        <?php if($story->parent > 0) echo isset($story->parentName) ? html::a(inlink('view', "storyID={$story->parent}&version=0&param=0&storyType=$story->type"), $story->parentName) . ' / ' : '';?><?php echo $story->title;?>
      </span>
      <?php if($story->version > 1):?>
      <small class='dropdown'>
        <a href='#' data-toggle='dropdown' class='text-muted'><?php echo '#' . $version;?> <span class='caret'></span></a>
        <ul class='dropdown-menu'>
        <?php
        for($i = $story->version; $i >= 1; $i --)
        {
            $class = $i == $version ? " class='active'" : '';
            echo '<li' . $class .'>' . html::a(inlink('view', "storyID=$story->id&version=$i&param=0&storyType=$story->type"), '#' . $i) . '</li>';
        }
        ?>
        </ul>
      </small>
      <?php endif; ?>
      <?php if($story->deleted):?>
      <span class='label label-danger'><?php echo $lang->story->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
  <?php if(!isonlybody()):?>
  <div class="btn-toolbar pull-right">
    <?php if(common::canModify('product', $product)): ?>
    <?php
    $otherParam = 'storyID=&projectID=';
    $tab        = 'product';
    if($this->app->rawModule == 'projectstory' or $this->app->tab == 'project')
    {
        $otherParam = "storyID=&projectID={$this->session->project}";
        $tab        = 'project';
    }
    else if($this->app->rawModule == 'execution')
    {
        $tab = 'execution';
    }
    ?>
    <?php common::printLink('story', 'create', "productID={$story->product}&branch={$story->branch}&moduleID={$story->module}&$otherParam&bugID=0&planID=0&todoID=0&extra=&storyType=$story->type", "<i class='icon icon-plus'></i> " . $lang->story->create, '', "class='btn btn-primary' data-app='$tab'"); ?>
    <?php endif;?>
  </div>
  <?php endif;?>
</div>
<?php if($this->app->getViewType() == 'xhtml'):?>
<div id="scrollContent">
<?php endif;?>
<?php endif;?>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->story->legendSpec;?></div>
        <div class="detail-content article-content"><?php echo $story->spec;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->story->legendVerify;?></div>
        <div class="detail-content article-content"><?php echo $story->verify;?></div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $story->files, 'fieldset' => 'true', 'object' => $story, 'method' => 'view', 'showDelete' => false));?>
      <?php
      $canBeChanged = common::canBeChanged('story', $story);
      if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=story&objectID=$story->id");
      ?>
      <?php if(!empty($story->children)):?>
      <div class='detail'>
        <div class='detail-title'><?php echo $this->lang->story->children;?></div>
        <div class='detail-content article-content'>
          <table class='table table-hover table-fixed'>
            <thead>
              <tr class='text-center'>
                <th class='w-50px'> <?php echo $lang->story->id;?></th>
                <th class='w-40px' title=<?php echo $lang->story->pri;?>><?php echo $lang->priAB;?></th>
                <th><?php echo $lang->story->title;?></th>
                <th class='w-100px'><?php echo $lang->story->assignedTo;?></th>
                <th class='w-90px'> <?php echo $lang->story->estimate;?></th>
                <th class='w-80px'> <?php echo $lang->story->status;?></th>
                <th class='w-230px'><?php echo $lang->actions;?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($story->children as $child):?>
              <tr class='text-center'>
                <td><?php echo $child->id;?></td>
                <td>
                  <?php
                  $priClass = $child->pri ? 'label-pri label-pri-' . $child->pri : '';
                  echo "<span class='$priClass'>";
                  echo $child->pri == '0' ? '' : zget($this->lang->story->priList, $child->pri, $child->pri);
                  echo "</span>";
                  ?>
                </td>
                <td class='text-left' title='<?php echo $child->title;?>'><a class="iframe" data-width="90%" href="<?php echo $this->createLink('story', 'view', "storyID=$child->id&version=0&param=0&storyType=$child->type", '', true); ?>"><?php echo $child->title;?></a></td>
                <td><?php echo zget($users, $child->assignedTo);?></td>
                <td title="<?php echo $child->estimate . ' ' . $lang->hourCommon;?>"><?php echo $child->estimate . $config->hourUnit;?></td>
                <td><?php echo $this->processStatus('story', $child);?></td>
                <td class='c-actions'>
                  <?php
                  common::printIcon('story', 'change',     "storyID=$child->id&from=&storyType=$child->type", $child, 'list', 'alter');
                  common::printIcon('story', 'review',     "storyID=$child->id&from=product&storyType=$child->type", $child, 'list', 'search', '', 'iframe showinonlybody', true);
                  common::printIcon('story', 'assignTo',   "storyID=$child->id&kanbanGroup=default&from=&storyType=$child->type", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('story', 'close',      "storyID=$child->id&from=&storyType=$child->type", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('story', 'activate',   "storyID=$child->id&storyType=$child->type", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('story', 'edit',       "storyID=$child->id&kanbanGroup=default&storyType=$child->type", $child, 'list');
                  common::printIcon('testcase', 'create', "productID=$child->product&branch=$child->branch&module=0&from=&param=0&story={$child->id}", $child, 'list', 'sitemap', '', 'iframe showinonlybody', true);
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endif;?>
    </div>
    <?php $this->printExtendFields($story, 'div', "position=left&inForm=0&inCell=1");?>
    <?php if($this->app->getViewType() != 'xhtml'):?>
    <div class="cell"><?php include '../../common/view/action.html.php';?></div>
    <?php endif;?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$story->deleted) echo $this->story->buildOperateMenu($story, 'view', $project);?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendBasicInfo' data-toggle='tab'><?php echo $lang->story->legendBasicInfo;?></a></li>
          <li><a href='#legendLifeTime' data-toggle='tab'><?php echo $lang->story->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendBasicInfo'>
            <table class="table table-data">
              <tbody>
                <?php if(!$product->shadow):?>
                <tr>
                  <th class='w-90px'><?php echo $lang->story->product;?></th>
                  <td><?php echo html::a($this->createLink('product', 'view', "productID=$story->product"), $product->name, '', "data-app='product'");?></td>
                </tr>
                <?php endif;?>
                <?php if($product->type != 'normal'):?>
                <tr>
                  <th class='w-90px'><?php echo $lang->product->branch;?></th>
                  <td>
                    <?php
                    if(isonlybody())
                    {
                        echo $branches[$story->branch];
                    }
                    else
                    {
                        common::printLink('product', 'browse', "productID=$story->product&branch=$story->branch", $branches[$story->branch], '', "data-app='product'");
                    }
                    ?>
                  </td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->story->module;?></th>
                  <?php
                  $moduleTitle = '';
                  ob_start();
                  if(empty($modulePath))
                  {
                      $moduleTitle .= '/';
                      echo "/";
                  }
                  else
                  {
                      if($storyModule->branch and isset($branches[$storyModule->branch]))
                      {
                          $moduleTitle .= $branches[$storyModule->branch] . '/';
                          echo $branches[$storyModule->branch] . $lang->arrow;
                      }

                      foreach($modulePath as $key => $module)
                      {
                          $moduleTitle .= $module->name;
                          if($product->shadow)
                          {
                              echo $module->name;
                          }
                          else
                          {
                              common::printLink('product', 'browse', "productID=$story->product&branch=$story->branch&browseType=byModule&param=$module->id", $module->name, '', "data-app='product'");
                          }
                          if(isset($modulePath[$key + 1]))
                          {
                              $moduleTitle .= '/';
                              echo $lang->arrow;
                          }
                      }
                  }
                  $printModule = ob_get_contents();
                  ob_end_clean();
                  ?>
                  <td title='<?php echo $moduleTitle?>'><?php echo $printModule?></td>
                </tr>
                <?php if($config->edition == 'ipd' && $story->type == 'requirement'):?>
                <tr>
                  <th><?php echo $lang->story->roadmap;?></th>
                  <td>
                  <?php
                  if($story->roadmap && isset($roadmaps[$story->roadmap]))
                  {
                      if(commonModel::hasPriv('roadmap', 'view'))
                      {
                          echo html::a($this->createLink('roadmap', 'view', "roadmapID={$story->roadmap}"), $roadmaps[$story->roadmap]);
                      }
                      else
                      {
                          echo $roadmaps[$story->roadmap];
                      }
                  }
                  ?>
                  </td>
                </tr>
                <?php endif;?>
                <?php if($story->type != 'requirement' and $story->parent != -1 and !$hiddenPlan):?>
                <tr class='plan-line'>
                  <th><?php echo $lang->story->plan;?></th>
                  <td>
                  <?php
                  if(isset($story->planTitle))
                  {
                      foreach($story->planTitle as $planID => $planTitle)
                      {
                          if(!common::printLink('productplan', 'view', "planID=$planID", $planTitle, '', "data-app='product'")) echo $planTitle;
                          echo '<br />';
                      }
                  }
                  ?>
                  </td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->story->source;?></th>
                  <td id='source'><?php echo $lang->story->sourceList[$story->source];?></td>
                </tr>
                <tr id='sourceNoteBox'>
                  <th><?php echo $lang->story->sourceNote;?></th>
                  <td><?php echo $story->sourceNote;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->status;?></th>
                  <td>
                    <?php $statusClass = $story->URChanged ? 'status-changed' : "status-{$story->status}";?>
                    <span class='status-story <?php echo $statusClass?>'>
                      <span class="label label-dot"></span>
                      <?php
                      if($story->URChanged)
                      {
                         echo $this->lang->story->URChanged;
                      }
                      else
                      {
                        echo $this->processStatus('story', $story);
                      }
                      ?>
                    </span>
                  </td>
                </tr>
                <?php if($story->type != 'requirement'):?>
                <tr class='stage-line'>
                  <th><?php echo $lang->story->stage;?></th>
                  <td>
                  <?php
                  $maxStage    = $story->stage;
                  $stageList   = join(',', array_keys($this->lang->story->stageList));
                  $maxStagePos = strpos($stageList, $maxStage);
                  if($story->stages and $branches)
                  {
                      foreach($story->stages as $branch => $stage)
                      {
                          if(strpos($stageList, $stage) !== false and strpos($stageList, $stage) > $maxStagePos)
                          {
                              $maxStage    = $stage;
                              $maxStagePos = strpos($stageList, $stage);
                          }
                      }
                  }
                  echo $lang->story->stageList[$maxStage];
                  ?>
                  </td>
                </tr>
                <?php endif;?>
                <tr class='categoryTR'>
                  <th><?php echo $lang->story->category;?></th>
                  <td><?php echo zget($lang->story->categoryList, $story->category, $story->category)?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->pri;?></th>
                  <td>
                    <?php if($story->pri):?>
                    <span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri)?></span>
                    <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->estimate;?></th>
                  <td title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
                </tr>
                <?php if(in_array($story->source, $config->story->feedbackSource)):?>
                <tr>
                  <th><?php echo $lang->story->feedbackBy;?></th>
                  <td><?php echo $story->feedbackBy;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->notifyEmail;?></th>
                  <td><?php echo $story->notifyEmail;?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->story->keywords;?></th>
                  <td><?php echo $story->keywords;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->legendMailto;?></th>
                  <td>
                  <?php
                  if(!empty($story->mailto))
                  {
                      foreach(explode(',', $story->mailto) as $account)
                      {
                          if(empty($account)) continue;
                          echo "<span>" . zget($users, trim($account)) . '</span> &nbsp;';
                      }
                  }
                  ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='legendLifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='thWidth'><?php echo $lang->story->openedBy;?></th>
                  <td><?php echo zget($users, $story->openedBy) . $lang->at . $story->openedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->assignedTo;?></th>
                  <td><?php if($story->assignedTo) echo zget($users, $story->assignedTo) . $lang->at . $story->assignedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->reviewers;?></th>
                  <td>
                    <?php
                    if($reviewers)
                    {
                        foreach($reviewers as $reviewer => $result)
                        {
                            echo !empty($result) ? '<span style="color: #cbd0db" title="' . $lang->story->reviewed . '"> ' . zget($users, $reviewer) . '</span>' : '<span title="' . $lang->story->toBeReviewed .'"> ' . zget($users, $reviewer) . '</span>';
                        }
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->reviewedDate;?></th>
                  <td><?php if($story->reviewedBy) echo $story->reviewedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->closedBy;?></th>
                  <td><?php if($story->closedBy) echo zget($users, $story->closedBy) . $lang->at . $story->closedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->closedReason;?></th>
                  <td class='resolution'>
                    <?php
                    if($story->closedReason) echo $lang->story->reasonList[$story->closedReason];
                    if(isset($story->extraStories[$story->duplicateStory]))
                    {
                        echo html::a(inlink('view', "storyID=$story->duplicateStory"), '#' . $story->duplicateStory . ' ' . $story->extraStories[$story->duplicateStory], '', "title='{$story->extraStories[$story->duplicateStory]}'");
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->lastEditedBy;?></th>
                  <td><?php if($story->lastEditedBy) echo zget($users, $story->lastEditedBy) . $lang->at . $story->lastEditedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <?php if(!empty($twins)):?>
          <li class='active'><a href='#legendTwins' data-toggle='tab'><?php echo $lang->story->twins;?></a></li>
          <?php endif;?>
          <?php if($this->config->URAndSR and !$hiddenURS and $config->vision != 'or'):?>
          <li class='<?php if(empty($twins)) echo 'active';?>'><a href='#legendStories' data-toggle='tab'><?php echo $story->type == 'story' ? $lang->story->requirement : $lang->story->story;?></a></li>
          <?php endif;?>
          <?php if($story->type == 'story' && common::hasPriv('story', 'tasks')):?>
          <li class="<?php if((!$this->config->URAndSR || $hiddenURS) and empty($twins)) echo 'active';?>"><a href='#legendProjectAndTask' data-toggle='tab'><?php echo $lang->story->legendProjectAndTask;?></a></li>
          <?php endif;?>
          <li <?php if($config->vision == 'or') echo "class='active'";?>><a href='#legendRelated' data-toggle='tab'><?php echo $lang->story->legendRelated;?></a></li>
        </ul>
        <div class='tab-content'>
          <?php if(!empty($twins)):?>
          <div class='tab-pane active' id='legendTwins'>
            <ul class="list-unstyled">
                <?php include './blocktwins.html.php';?>
            </ul>
          </div>
          <?php endif;?>
          <?php if($this->config->URAndSR and !$hiddenURS and $config->vision != 'or'):?>
          <div class='tab-pane <?php if(empty($twins)) echo 'active';?>' id='legendStories'>
            <ul class="list-unstyled">
              <?php
              $relation         = array();
              $relationType     = $story->type == 'story' ? 'requirement' : 'story';
              $canViewLinkStory = common::hasPriv($relationType, 'view', null, "storyType=$relationType");
              foreach($relations as $item) $relation[$item->id] = $item->title;
              foreach($relation as $id => $title)
              {
                  echo "<li title='$title' class='legendStories'>" . ($canViewLinkStory ? html::a($this->createLink('story', 'view', "id=$id&version=0&param=0&storyType=$relationType", '', true), "#$id $title", '', "class='iframe' data-width='80%'") : "#$id $title");
                  echo html::a($this->createLink('story', 'linkStory', "storyID=$story->id&type=remove&linkedID=$id&browseType=&queryID=0&storyType=$story->type"), '<i class="icon icon-unlink btn-info"></i>', 'hiddenwin', "class='hide removeButton'");
              }
              ?>
              <?php $linkLang = ($story->type == 'story') ? $lang->story->requirement : $lang->story->story;?>
              <li><?php if(common::hasPriv($story->type, 'linkStory')) echo html::a($this->createLink('story', 'linkStory', "storyID=$story->id&type=linkStories&linkedID=0&browseType=&queryID=0&storyType=$story->type", '', true), $lang->story->link . $linkLang, '', "class='btn btn-info iframe' data-width='95%' id='linkButton'");?>
            </ul>
          </div>
          <?php endif;?>

          <?php if($story->type == 'story'):?>
          <div class="tab-pane <?php if((!$this->config->URAndSR || $hiddenURS) and empty($twins)) echo 'active';?>" id='legendProjectAndTask'>
            <ul class="list-unstyled">
              <?php
              foreach($story->tasks as $executionTasks)
              {
                  foreach($executionTasks as $task)
                  {
                      if(!isset($executions[$task->execution])) continue;
                      $execution     = isset($story->executions[$task->execution]) ? $story->executions[$task->execution] : '';
                      $executionLink = !empty($execution->multiple) ? $this->createLink('execution', 'view', "executionID=$task->execution") : $this->createLink('project', 'view', "projectID=$task->project");
                      $executionName = $executions[$task->execution];
                      $taskInfo      = $task->id . '&nbsp<span class="label label-success label-outline">' . $this->lang->task->statusList[$task->status]  . '</span>&nbsp' . $task->name;
                      $class         = isonlybody() ? 'showinonlybody' : 'iframe';
                      $execName  = (isset($execution->type) and $execution->type == 'kanban' and isonlybody()) ? $executionName : html::a($executionLink, $executionName, '', "class='text-muted'");
                      echo "<li title='$task->name'>" . $execName . html::a($this->createLink('task', 'view', "taskID=$task->id", '', true), $taskInfo, '', "class=$class data-width='90%'") . '</li>';
                  }
              }
              foreach($story->executions as $executionID => $execution)
              {
                  if(!$execution->multiple) continue;
                  if(!isset($executions[$executionID])) continue;
                  if(isset($story->tasks[$executionID])) continue;

                  $execName = ($execution->type == 'kanban' and isonlybody()) ? $executions[$executionID] : html::a($this->createLink('execution', 'view', "executionID=$executionID"), $executions[$executionID], '', "class='text-muted'");
                  echo "<li title='$execution->name'>" . $execName . '</li>';
              }
              ?>
            </ul>
          </div>
          <?php endif;?>
          <div class="tab-pane <?php if($config->vision == 'or') echo 'active';?>" id='legendRelated'>
            <table class="table table-data">
              <tbody>
                <?php if($story->type == 'story'):?>
                <?php if(common::hasPriv('story', 'bugs')):?>
                <?php if(!empty($fromBug)):?>
                <tr>
                  <th><?php echo $lang->story->legendFromBug;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                    <?php echo "<li title='#$fromBug->id $fromBug->title'>" . html::a($this->createLink('bug', 'view', "bugID=$fromBug->id", '', true), "#$fromBug->id $fromBug->title", '', "class='iframe' data-width='80%'") . '</li>';?>
                    </ul>
                  </td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->story->legendBugs;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                    <?php
                    foreach($bugs as $bug)
                    {
                        $bugInfo = "#$bug->id" . '&nbsp<span class="status-bug status-' . $bug->status .'">' . $this->lang->bug->statusList[$bug->status]  . '</span>&nbsp' . $bug->title;
                        echo "<li title='$bug->title'>" . html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), $bugInfo, '', "class='iframe' data-width='80%'") . '</li>';
                    }
                    ?>
                    </ul>
                  </td>
                </tr>
                <?php endif;?>
                <?php if(common::hasPriv('story', 'cases')):?>
                <tr>
                  <th><?php echo $lang->story->legendCases;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                    <?php
                    $misc = isonlybody() ? "showinonlybody" : "class='iframe' data-width='80%'";

                    foreach($cases as $case)
                    {
                        echo "<li title='$case->title'>" . html::a($this->createLink('testcase', 'view', "caseID=$case->id", '', true), "#$case->id $case->title", '', $misc) . '</li>';
                    }
                    ?>
                    </ul>
                  </td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->story->legendBuilds;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                    <?php
                    $tab = $app->tab == 'product' ? 'project' : $app->tab;
                    foreach($builds as $build)
                    {
                        $link = common::hasPriv('build', 'view') ? html::a($this->createLink('build', 'view', "buildID=$build->id"), "#$build->id $build->name", '', "data-app='{$tab}'") : "#$build->id $build->name";
                        echo "<li title='$build->name'>$link</li>";
                    }
                    ?>
                    </ul>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->legendReleases;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                    <?php
                    $tab           = $app->tab == 'execution' ? 'product'        : $app->tab;
                    $releaseModule = $app->tab == 'project'   ? 'projectrelease' : 'release';
                    foreach($releases as $release)
                    {
                        $link = common::hasPriv($releaseModule, 'view') ? html::a($this->createLink($releaseModule, 'view', "release=$release->id"), "#$release->id $release->name", '', "data-app='{$tab}'") : "#$release->id $release->name";
                        echo "<li title='$release->name'>$link</li>";
                    }
                    ?>
                    </ul>
                  </td>
                </tr>
                <?php endif;?>
                <?php if(common::hasPriv($story->type, 'relation')):?>
                <tr class='text-top linkStoryTr'>
                  <th><?php echo $lang->story->linkStories;?></th>
                  <td>
                    <ul class='list-unstyled'>
                      <?php
                      if(isset($story->linkStoryTitles))
                      {
                          $iframe = isonlybody() ? '' : 'iframe';
                          foreach($story->linkStoryTitles as $linkStoryID => $linkStoryTitle)
                          {
                              if($app->user->admin or strpos(",{$app->user->view->products},", ",{$storyProducts[$linkStoryID]},") !== false)
                              {
                                  $storyLink = html::a($this->createLink('story', 'view', "storyID=$linkStoryID&version=0&param=0&storyType=$story->type", '', true), "#$linkStoryID $linkStoryTitle", '', "class='{$iframe}' data-width='80%' title='$linkStoryTitle'") . '<br />';
                              }
                              else
                              {
                                  $storyLink = "#$linkStoryID $linkStoryTitle";
                              }
                              echo "<li title='$linkStoryTitle' class='linkStoryTitle'>$storyLink</li>";
                          }
                      }
                      ?>
                    </ul>
                  </td>
                </tr>
                <?php endif;?>
                <?php if($story->type == 'story' and helper::hasFeature('devops')):?>
                <tr>
                  <th><?php echo $lang->story->linkMR;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                    <?php
                    $mrPriv = common::hasPriv('mr', 'view');
                    foreach($linkedMRs as $MRID => $linkMRTitle)
                    {
                        if($mrPriv)
                        {
                            echo "<li title='$linkMRTitle'>" . html::a($this->createLink('mr', 'view', "MRID=$MRID"), "#$MRID $linkMRTitle", '', 'data-app="devops"') . '</li>';
                        }
                        else
                        {
                            echo "<li title='$linkMRTitle'>" . "#$MRID $linkMRTitle" . '</li>';
                        }
                    }
                    ?>
                    </ul>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->linkCommit;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                    <?php
                    $canViewRevision = common::hasPriv('repo', 'revision');
                    foreach($linkedCommits as $commit)
                    {
                        $revision    = substr($commit->revision, 0, 10);
                        $commitTitle = $revision . ' ' . $commit->comment;
                        if($canViewRevision)
                        {
                            echo "<li class='link-commit' title='$commitTitle'>" . html::a($this->createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}"), $revision, '', 'data-app="devops"') . ' ' . $commit->comment . '</li>';
                        }
                        else
                        {
                            echo "<li class='link-commit' title='$commitTitle'>" . "$commitTitle" . '</li>';
                        }
                    }
                    ?>
                    </ul>
                  </td>
                </tr>
                <?php endif;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php $this->printExtendFields($story, 'div', "position=right&inForm=0&inCell=1");?>
  </div>
</div>
<?php if($this->app->getViewType() == 'xhtml'):?>
</div>
<?php endif;?>

<?php if(in_array($config->edition, array('max', 'ipd'))):?>
<div class="modal fade" id="importToLib">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->story->importToLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('story', 'importToLib', "storyID=$story->id");?>'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->story->lib;?></th>
              <td>
                <?php echo html::select('lib', $libs, '', "class='form-control chosen' required");?>
              </td>
            </tr>
            <?php if(!common::hasPriv('assetlib', 'approveStory') and !common::hasPriv('assetlib', 'batchApproveStory')):?>
            <tr>
              <th><?php echo $lang->story->approver;?></th>
              <td>
                <?php echo html::select('assignedTo', $approvers, '', "class='form-control chosen'");?>
              </td>
            </tr>
            <?php endif;?>
            <tr>
              <td colspan='2' class='text-center'>
                <?php echo html::submitButton($lang->import, '', 'btn btn-primary');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif;?>

<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext);?>
</div>
<?php
js::set('canCreate', common::hasPriv('story', 'story'));
js::set('createStory', $lang->story->create);
js::set('productID', $story->product);
js::set('branch', $story->branch);
js::set('moduleID', $story->module);
js::set('storyType', $story->type);
js::set('unlink', $lang->story->unlink);
js::set('cancel', $lang->cancel);
js::set('rawModule', $this->app->rawModule);
?>

<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
