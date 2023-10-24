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
<?php include $this->app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $this->app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php $browseLink = $app->session->storyList ? $app->session->storyList : $this->createLink('product', 'browse', "productID=$story->product");?>
<?php js::set('sysurl', common::getSysUrl());?>
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
    <?php if(common::canModify('product', $product)): ?>
    <?php
    $otherParam = 'storyID=&projectID=';
    if($this->app->rawModule == 'projectstory') $otherParam = "storyID=&projectID={$this->session->project}";
    ?>
    <?php common::printLink('story', 'create', "productID={$story->product}&branch={$story->branch}&moduleID={$story->module}&$otherParam&bugID=0&planID=0&todoID=0&extra=&type=$story->type", "<i class='icon icon-plus'></i> " . $lang->story->create, '', "class='btn btn-primary' data-app='project'"); ?>
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
                <th class='w-40px'> <?php echo $lang->priAB;?></th>
                <th>                <?php echo $lang->story->title;?></th>
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
                  echo "<span class='pri-" . $child->pri . "'>";
                  echo $child->pri == '0' ? '' : zget($this->lang->story->priList, $child->pri, $child->pri);
                  echo "</span>";
                  ?>
                </td>
                <td class='text-left' title='<?php echo $child->title;?>'><a class="iframe" data-width="90%" href="<?php echo $this->createLink('story', 'view', "storyID=$child->id", '', true); ?>"><?php echo $child->title;?></a></td>
                <td><?php echo zget($users, $child->assignedTo);?></td>
                <td title="<?php echo $child->estimate . ' ' . $lang->hourCommon;?>"><?php echo $child->estimate . $config->hourUnit;?></td>
                <td><?php echo $this->processStatus('story', $child);?></td>
                <td class='c-actions'>
                  <?php
                  common::printIcon('story', 'change', "storyID=$child->id", $child, 'list', 'alter');
                  if(strpos('draft,changing', $child->status) !== false)
                  {
                      common::printIcon('story', 'submitReview', "storyID=$child->id", $child, 'list', 'confirm', '', 'iframe showinonlybody', true);
                  }
                  else
                  {
                      common::printIcon('story', 'review', "storyID=$child->id", $child, 'list', 'search', '', 'iframe showinonlybody', true);
                  }
                  common::printIcon('story', 'assignTo', "storyID=$child->id", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('story', 'close',  "storyID=$child->id", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('story', 'activate', "storyID=$child->id", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('story', 'edit',   "storyID=$child->id", $child, 'list');
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
    <div class="cell"><?php include $this->app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <?php endif;?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$story->deleted):?>
        <?php
        common::printIcon('story', 'change', "storyID=$story->id", $story, 'button', 'alter', '', 'showinonlybody');
        if(strpos('draft,changing', $story->status) !== false)
        {
            common::printIcon('story', 'submitReview', "storyID=$story->id", $story, 'button', 'confirm', '', 'iframe showinonlybody', true);
        }
        else
        {
            common::printIcon('story', 'review', "storyID=$story->id", $story, 'button', 'search', '', 'iframe showinonlybody', true);
        }
        if($story->status == 'active' and $story->stage == 'wait' and $story->parent <= 0 and !isonlybody())
        {
            $divideLang = $lang->story->subdivide;
            $misc       = "class='btn divideStory' data-toggle='modal' data-type='iframe' data-width='95%'";
            $link       = $this->createLink('story', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id", '', true);
            if(common::hasPriv('story', 'batchCreate', $story)) echo html::a($link, "<i class='icon icon-split'></i> " . $divideLang, '', $misc);
        }

        common::printIcon('story', 'assignTo', "storyID=$story->id", $story, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('story', 'close',    "storyID=$story->id", $story, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('story', 'activate', "storyID=$story->id", $story, 'button', '', '', 'iframe showinonlybody', true);

        if($from == 'execution' and strpos('draft,reviewing,closed', $story->status) === false) common::printIcon('task', 'create', "execution=$param&storyID=$story->id&moduleID=$story->module", $story, 'button', 'plus', '', 'showinonlybody');

        echo "<div class='divider'></div>";
        common::printIcon('story', 'edit', "storyID=$story->id", $story);
        common::printIcon('story', 'create', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id&executionID=0&bugID=0&planID=0&todoID=0&extra=&type=$story->type", $story, 'button', 'copy', '', '', '', "data-width='1050'");
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
                <tr class='w-90px'>
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
                          if(!common::printLink('projectstory', 'story', "projectID={$this->session->project}&productID=$story->product&branch=$story->branch&browseType=byModule&param=$module->id", $module->name, '', "data-app='project'")) echo $module->name;
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
                <tr>
                  <th><?php echo $lang->story->status;?></th>
                  <td><span class='status-story status-<?php echo $story->status?>'><span class="label label-dot"></span> <?php echo $this->processStatus('story', $story);?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->pri;?></th>
                  <td><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri)?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->estimate;?></th>
                  <td title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
                </tr>
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
          <li class="active"><a href='#legendProjectAndTask' data-toggle='tab'><?php echo $lang->story->legendProjectAndTask;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class="tab-pane active" id='legendProjectAndTask'>
            <ul class="list-unstyled">
              <?php
              foreach($story->tasks as $executionTasks)
              {
                  foreach($executionTasks as $task)
                  {
                      if(!isset($executions[$task->execution])) continue;
                      $executionName = $executions[$task->execution];
                      $taskInfo      = $task->id . '&nbsp<span class="label label-success label-outline">' . $this->lang->task->statusList[$task->status]  . '</span>&nbsp' . $task->name;
                      $class         = isonlybody() ? 'showinonlybody' : 'iframe';
                      echo "<li title='$task->name'>" . html::a($this->createLink('task', 'view', "taskID=$task->id", '', true), $taskInfo, '', "class=$class data-width='80%'");
                      echo html::a($this->createLink('execution', 'browse', "executionID=$task->execution"), $executionName, '', "class='text-muted'") . '</li>';
                  }
              }
              if(count($story->tasks) == 0)
              {
                  foreach($story->executions as $executionID => $execution)
                  {
                      echo "<li title='$execution->name'>" . html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name, '', "class='text-muted'") . '</li>';
                  }
              }
              ?>
            </ul>
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
<script>
</script>
<?php include $this->app->getModuleRoot() . 'common/view/syntaxhighlighter.html.php';?>
<?php include $this->app->getModuleRoot() . 'common/view/footer.html.php';?>
