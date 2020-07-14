<?php
/**
 * The view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: view.html.php 4952 2013-07-02 01:14:58Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php $browseLink = $app->session->storyList != false ? $app->session->storyList : $this->createLink('product', 'browse', "productID=$story->product&branch=$story->branch&moduleID=$story->module");?>
<?php js::set('sysurl', common::getSysUrl());?>
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
        <?php if($story->parent > 0) echo isset($story->parentName) ? html::a(inlink('view', "storyID={$story->parent}"), $story->parentName) . ' / ' : '';?><?php echo $story->title;?>
      </span>
      <?php if($story->version > 1):?>
      <small class='dropdown'>
        <a href='#' data-toggle='dropdown' class='text-muted'><?php echo '#' . $version;?> <span class='caret'></span></a>
        <ul class='dropdown-menu'>
        <?php
        for($i = $story->version; $i >= 1; $i --)
        {
      	  $class = $i == $version ? " class='active'" : '';
      	  echo '<li' . $class .'>' . html::a(inlink('view', "storyID=$story->id&version=$i"), '#' . $i) . '</li>';
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
    <?php common::printLink('story', 'create', "productID={$story->product}&branch={$story->branch}&moduleID={$story->module}", "<i class='icon icon-plus'></i>" . $lang->story->create, '', "class='btn btn-primary'"); ?>
  </div>
  <?php endif;?>
</div>
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
      <?php echo $this->fetch('file', 'printFiles', array('files' => $story->files, 'fieldset' => 'true'));?>
      <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=story&objectID=$story->id");?>
      <?php if(!empty($story->children)):?>
      <div class='detail'>
        <div class='detail-title'><?php echo $this->lang->story->children;?></div>
        <div class='detail-content article-content'>
          <table class='table table-hover table-fixed'>
            <thead>
              <tr class='text-center'>
                <th class='w-50px'> <?php echo $lang->story->id;?></th>
                <th class='w-40px'> <?php echo $lang->priAB;?></th>
                <th>                <?php echo $lang->story->title;?></th>
                <th class='w-100px'><?php echo $lang->story->assignedTo;?></th>
                <th class='w-80px'> <?php echo $lang->story->estimate;?></th>
                <th class='w-80px'> <?php echo $lang->story->status;?></th>
                <th class='w-200px'><?php echo $lang->actions;?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($story->children as $child):?>
              <tr class='text-center'>
                <td><?php echo $child->id;?></td>
                <td>
                  <?php
                  echo "<span class='pri-" . $child->pri . "'>";
                  echo $child->pri == '0' ? '' : zget($this->lang->story->priList, $child->pri, $child->pri);
                  echo "</span>";
                  ?>
                </td>
                <td class='text-left' title='<?php echo $child->title;?>'><a class="iframe" data-width="90%" href="<?php echo $this->createLink('story', 'view', "storyID=$child->id", '', true); ?>"><?php echo $child->title;?></a></td>
                <td><?php echo zget($users, $child->assignedTo);?></td>
                <td><?php echo $child->estimate;?></td>
                <td><?php echo $this->processStatus('story', $child);?></td>
                <td class='c-actions'>
                  <?php
                  common::printIcon('story', 'change', "storyID=$child->id", $child, 'list');
                  common::printIcon('story', 'review', "storyID=$child->id", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('story', 'assignTo', "storyID=$child->id", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('story', 'close',  "storyID=$child->id", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('story', 'activate', "storyID=$child->id", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('story', 'edit',   "storyID=$child->id", $child, 'list');
                  common::printIcon('story', 'createCase', "productID=$child->product&branch=$child->branch&module=0&from=&param=0&story={$child->id}", $child, 'list', 'sitemap');
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
    <div class="cell"><?php include '../../common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$story->deleted):?>
        <?php
        common::printIcon('story', 'change', "storyID=$story->id", $story, 'button', '', '', 'showinonlybody');
        common::printIcon('story', 'review', "storyID=$story->id", $story, 'button', '', '', 'showinonlybody');
        if($story->status == 'active' and $story->stage == 'wait' and $story->parent <= 0 and !isonlybody())
        {
            $divideLang = ($story->type == 'story' || !$story->type) ? $lang->story->subdivide : $lang->story->splitRequirent; 
            $misc       = "class='btn divideStory' data-toggle='modal' data-type='iframe' data-width='95%'";
            $link       = $this->createLink('story', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id", '', true);
            if(common::hasPriv('story', 'batchCreate')) echo html::a($link, "<i class='icon icon-treemap-alt'></i> " . $divideLang, '', $misc);
        }

        common::printIcon('story', 'assignTo', "storyID=$story->id", $story, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('story', 'close',    "storyID=$story->id", $story, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('story', 'activate', "storyID=$story->id", $story, 'button', '', '', 'iframe showinonlybody', true);

        if($config->global->flow != 'onlyStory' and !isonlybody() and $story->parent >= 0 and $story->type != 'requirement' and (common::hasPriv('testcase', 'create') or common::hasPriv('testcase', 'batchCreate')))
        {
            $this->app->loadLang('testcase');
            echo "<div class='btn-group dropup'>";
            echo "<button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><i class='icon icon-sitemap'></i> " . $lang->testcase->common . " <span class='caret'></span></button>";
            echo "<ul class='dropdown-menu' id='createCaseActionMenu'>";

            $misc = "data-toggle='modal' data-type='iframe' data-width='95%'";
            $link = $this->createLink('testcase', 'create', "productID=$story->product&branch=$story->branch&moduleID=0&from=&param=0&storyID=$story->id", '', true);
            if(common::hasPriv('testcase', 'create', $story)) echo "<li>" . html::a($link, $lang->testcase->create, '', $misc) . "</li>";

            $misc = "data-toggle='modal' data-type='iframe' data-width='95%'";
            $link = $this->createLink('testcase', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=0&storyID=$story->id", '', true);
            if(common::hasPriv('testcase', 'batchCreate')) echo "<li>" . html::a($link, $lang->testcase->batchCreate, '', $misc) . "</li>";

            echo "</ul>";
            echo "</div>";
        }

        if($from == 'project') common::printIcon('task', 'create', "project=$param&storyID=$story->id&moduleID=$story->module", $story, 'button', 'plus', '', 'showinonlybody');

        echo $this->buildOperateMenu($story, 'view');

        echo "<div class='divider'></div>";
        common::printIcon('story', 'edit', "storyID=$story->id", $story);
        common::printIcon('story', 'create', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id&projectID=0&bugID=0&planID=0&todoID=0&extra=&type=$story->type", $story, 'button', 'copy', '', '', '', "data-width='1050'");
        common::printIcon('story', 'delete', "storyID=$story->id", $story, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
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
                <tr>
                  <th class='w-90px'><?php echo $lang->story->product;?></th>
                  <td><?php echo html::a($this->createLink('product', 'view', "productID=$story->product"), $product->name);?></td>
                </tr>
                <?php if($product->type != 'normal'):?>
                <tr>
                  <th><?php echo $lang->product->branch;?></th>
                  <td><?php common::printLink('product', 'browse', "productID=$story->product&branch=$story->branch", $branches[$story->branch]);?></td>
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
                          if(!common::printLink('product', 'browse', "productID=$story->product&branch=$story->branch&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
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
                <tr class='plan-line'>
                  <th><?php echo $lang->story->plan;?></th>
                  <td>
                  <?php
                  if(isset($story->planTitle))
                  {
                      foreach($story->planTitle as $planID => $planTitle)
                      {
                          if(!common::printLink('productplan', 'view', "planID=$planID", $planTitle)) echo $lanTitle;
                          echo '<br />';
                      }
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->source;?></th>
                  <td id='source'><?php echo $lang->story->sourceList[$story->source];?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->sourceNote;?></th>
                  <td><?php echo $story->sourceNote;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->status;?></th>
                  <td><span class='status-story status-<?php echo $story->status?>'><span class="label label-dot"></span> <?php echo $this->processStatus('story', $story);?></span></td>
                </tr>
                <tr class='stage-line'>
                  <th><?php echo $lang->story->stage;?></th>
                  <td>
                  <?php
                  if($story->stages and $branches)
                  {
                      foreach($story->stages as $branch => $stage) if(isset($branches[$branch])) echo $branches[$branch] . ' : ' . $lang->story->stageList[$stage] . '<br />';
                  }
                  else
                  {
                      echo $lang->story->stageList[$story->stage];
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->pri;?></th>
                  <td><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri)?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->estimate;?></th>
                  <td><?php echo $story->estimate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->keywords;?></th>
                  <td><?php echo $story->keywords;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->legendMailto;?></th>
                  <td><?php $mailto = explode(',', $story->mailto); foreach($mailto as $account) {if(empty($account)) continue; echo "<span>" . zget($users, trim($account)) . '</span> &nbsp;'; }?></td>
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
                  <th><?php echo $lang->story->reviewedBy;?></th>
                  <td><?php $reviewedBy = explode(',', $story->reviewedBy); foreach($reviewedBy as $account) echo ' ' . zget($users, trim($account)); ?></td>
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
                  <td>
                    <?php
                    if($story->closedReason) echo $lang->story->reasonList[$story->closedReason];
                    if(isset($story->extraStories[$story->duplicateStory]))
                    {
                        echo html::a(inlink('view', "storyID=$story->duplicateStory"), '#' . $story->duplicateStory . ' ' . $story->extraStories[$story->duplicateStory]);
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
          <?php if($config->global->flow == 'onlyStory'):?>
          <li class='active'><a href='#legendRelated' data-toggle='tab'><?php echo $lang->story->legendRelated;?></a></li>
          <?php else:?>
          <li class='active'><a href='#legendProjectAndTask' data-toggle='tab'><?php echo $lang->story->legendProjectAndTask;?></a></li>
          <li><a href='#legendRelated' data-toggle='tab'><?php echo $lang->story->legendRelated;?></a></li>
          <?php endif;?>
        </ul>
        <div class='tab-content'>
          <?php if($config->global->flow != 'onlyStory'):?>
          <div class='tab-pane active' id='legendProjectAndTask'>
            <ul class="list-unstyled">
              <?php
              foreach($story->tasks as $projectTasks)
              {
                  foreach($projectTasks as $task)
                  {
                      if(!isset($projects[$task->project])) continue;
                      $projectName = $projects[$task->project];
                      $class = isonlybody() ? 'showinonlybody' : 'iframe';
                      echo "<li title='$task->name'>" . html::a($this->createLink('task', 'view', "taskID=$task->id", '', true), "[T]$task->id $task->name", '', "class=$class data-width='80%'");
                      echo html::a($this->createLink('project', 'browse', "projectID=$task->project"), $projectName, '', "class='text-muted'") . '</li>';
                  }
              }
              if(count($story->tasks) == 0)
              {
                  foreach($story->projects as $projectID => $project)
                  {
                      echo "<li title='$project->name'>" . html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name, '', "class='text-muted'") . '</li>';
                  }
              }
              ?>
            </ul>
          </div>
          <?php endif;?>
          <div class="tab-pane <?php if($config->global->flow == 'onlyStory') echo 'active';?>" id='legendRelated'>
            <table class="table table-data">
              <tbody>
                <?php if($config->global->flow != 'onlyStory'):?>
                <?php if(!empty($fromBug)):?>
                <tr class='text-top'>
                  <th class='thWidth'><?php echo $lang->story->legendFromBug;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                    <?php echo "<li title='#$fromBug->id $fromBug->title'>" . html::a($this->createLink('bug', 'view', "bugID=$fromBug->id", '', true), "#$fromBug->id $fromBug->title", '', "class='iframe' data-width='80%'") . '</li>';?>
                    </ul>
                  </td>
                </tr>
                <?php endif;?>
                <tr>
                  <th class='text-top thWidth'><?php echo $lang->story->legendBugs;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                    <?php
                    foreach($bugs as $bug)
                    {
                        echo "<li title='[B]$bug->id $bug->title'>" . html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), "[B] #$bug->id $bug->title", '', "class='iframe' data-width='80%'") . '</li>';
                    }
                    ?>
                    </ul>
                  </td>
                </tr>
                <tr>
                  <th class='text-top'><?php echo $lang->story->legendCases;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                    <?php
                    foreach($cases as $case)
                    {
                        echo "<li title='[C]$case->id $case->title'>" . html::a($this->createLink('testcase', 'view', "caseID=$case->id", '', true), "[C] #$case->id $case->title", '', "class='iframe' data-width='80%'") . '</li>';
                    }
                    ?>
                    </ul>
                  </td>
                </tr>
                <?php endif;?>
                <tr>
                  <th class='text-top thWidth'><?php echo $lang->story->legendLinkStories;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                      <?php
                      $linkStories = explode(',', $story->linkStories) ;
                      foreach($linkStories as $linkStoryID)
                      {
                          if(isset($story->extraStories[$linkStoryID])) echo '<li>' . html::a($this->createLink('story', 'view', "storyID=$linkStoryID", '', true), "[S] #$linkStoryID " . $story->extraStories[$linkStoryID], '', "class='iframe' data-width='80%'") . '</li>';
                      }
                      ?>
                    </ul>
                  </td>
                </tr>
                <tr>
                  <th class='text-top'><?php echo $lang->story->legendChildStories;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                      <?php
                      $childStories = explode(',', $story->childStories) ;
                      foreach($childStories as $childStoryID)
                      {
                        if(isset($story->extraStories[$childStoryID])) echo '<li>' . html::a($this->createLink('story', 'view', "storyID=$childStoryID", '', true), "#$childStoryID " . $story->extraStories[$childStoryID], '', "class='iframe' data-width='80%'") . '</li>';
                      }
                      ?>
                    </ul>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php $this->printExtendFields($story, 'div', "position=right&inForm=0&inCell=1");?>
  </div>
</div>

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
?>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
