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
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-link'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $story->id?></span>
      <span class="text" title='<?php echo $story->title;?>' style='color: <?php echo $story->color;?>'><?php echo $story->title;?></span>
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
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->story->legendBasicInfo;?></summary>
        <div class="detail-content">
          <table class="table table-data">
            <tbody>
              <tr>
                <th><?php echo $lang->story->product;?></th>
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
              <tr>
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
                <td><span class='status-<?php echo $story->status?>'><span class="label label-dot"></span> <?php echo $lang->story->statusList[$story->status];?></span></td>
              </tr>
              <tr>
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
                <td><?php $mailto = explode(',', $story->mailto); foreach($mailto as $account) {if(empty($account)) continue; echo "<span>" . $users[trim($account)] . '</span> &nbsp;'; }?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </details>
    </div>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->story->legendProjectAndTask;?></summary>
        <div class="detail-content">
          <ul class="list-unstyled no-margin">
            <?php
            foreach($story->tasks as $projectTasks)
            {
                foreach($projectTasks as $task)
                {
                    if(!isset($projects[$task->project])) continue;
                    $projectName = $projects[$task->project];
                    echo "<li title='$task->name'>" . html::a($this->createLink('task', 'view', "taskID=$task->id", '', true), "#$task->id $task->name", '', "class='iframe' data-width='80%'");
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
      </details>
    </div>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->story->legendRelated;?></summary>
        <div class="detail-content">
          <table class="table table-data">
            <tbody>
              <?php if($config->global->flow != 'onlyStory'):?>
              <?php if(!empty($fromBug)):?>
              <tr class='text-top'>
                <th class='w-70px'><?php echo $lang->story->legendFromBug;?></th>
                <td class='pd-0'>
                  <ul class='list-unstyled'>
                  <?php echo "<li title='#$fromBug->id $fromBug->title'>" . html::a($this->createLink('bug', 'view', "bugID=$fromBug->id", '', true), "#$fromBug->id $fromBug->title", '', "class='iframe' data-width='80%'") . '</li>';?>
                  </ul>
                </td>
              </tr>
              <?php endif;?>
              <tr class='text-top'>
                <th class='w-70px'><?php echo $lang->story->legendBugs;?></th>
                <td class='pd-0'>
                  <ul class='list-unstyled'>
                  <?php
                  foreach($bugs as $bug)
                  {
                      echo "<li title='#$bug->id $bug->title'>" . html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), "#$bug->id $bug->title", '', "class='iframe' data-width='80%'") . '</li>';
                  }
                  ?>
                  </ul>
                </td>
              </tr>
              <tr class='text-top'>
                <th><?php echo $lang->story->legendCases;?></th>
                <td class='pd-0'>
                  <ul class='list-unstyled'>
                  <?php
                  foreach($cases as $case)
                  {
                      echo "<li title='#$case->id $case->title'>" . html::a($this->createLink('testcase', 'view', "caseID=$case->id", '', true), "#$case->id $case->title", '', "class='iframe' data-width='80%'") . '</li>';
                  }
                  ?>
                  </ul>
                </td>
              </tr>
              <?php endif;?>
              <tr class='text-top'>
                <th class='w-80px'><?php echo $lang->story->legendLinkStories;?></th>
                <td class='pd-0'>
                  <ul class='list-unstyled'>
                    <?php
                    $linkStories = explode(',', $story->linkStories) ;
                    foreach($linkStories as $linkStoryID)
                    {
                        if(isset($story->extraStories[$linkStoryID])) echo '<li>' . html::a($this->createLink('story', 'view', "storyID=$linkStoryID", '', true), "#$linkStoryID " . $story->extraStories[$linkStoryID], '', "class='iframe' data-width='80%'") . '</li>';
                    }
                    ?>
                  </ul>
                </td>
              </tr>
              <tr class='text-top'>
                <th><?php echo $lang->story->legendChildStories;?></th>
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
      </details>
    </div>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->story->legendLifeTime;?></summary>
        <div class="detail-content">
          <table class="table table-data">
            <tbody>
              <tr>
                <th class='w-70px'><?php echo $lang->story->openedBy;?></th>
                <td><?php echo $users[$story->openedBy] . $lang->at . $story->openedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->assignedTo;?></th>
                <td><?php if($story->assignedTo) echo $users[$story->assignedTo] . $lang->at . $story->assignedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->reviewedBy;?></th>
                <td><?php $reviewedBy = explode(',', $story->reviewedBy); foreach($reviewedBy as $account) echo ' ' . $users[trim($account)]; ?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->reviewedDate;?></th>
                <td><?php if($story->reviewedBy) echo $story->reviewedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->closedBy;?></th>
                <td><?php if($story->closedBy) echo $users[$story->closedBy] . $lang->at . $story->closedDate;?></td>
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
                <td><?php if($story->lastEditedBy) echo $users[$story->lastEditedBy] . $lang->at . $story->lastEditedDate;?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </details>
    </div>
  </div>
</div>

<div id="mainActions">
  <?php common::printPreAndNext($preAndNext);?>
  <div class="btn-toolbar">
    <?php common::printBack($browseLink);?>
    <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
    <?php if(!$story->deleted):?>
    <?php
    common::printIcon('story', 'change', "storyID=$story->id", $story, 'button', '', '', 'showinonlybody');
    common::printIcon('story', 'review', "storyID=$story->id", $story, 'button', '', '', 'showinonlybody');
    if($story->status != 'closed' and !isonlybody())
    {
        $misc = "class='btn' data-toggle='modal' data-type='iframe' data-width='95%'";
        $link = $this->createLink('story', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id", '', true);
        if(common::hasPriv('story', 'batchCreate')) echo html::a($link, "<i class='icon icon-sitemap'></i> " . $lang->story->subdivide, '', $misc);
    }

    common::printIcon('story', 'close',    "storyID=$story->id", $story, 'button', '', '', 'iframe showinonlybody', true);
    common::printIcon('story', 'activate', "storyID=$story->id", $story, 'button', '', '', 'iframe showinonlybody', true);

    if($config->global->flow != 'onlyStory' and !isonlybody() and (common::hasPriv('testcase', 'create') or common::hasPriv('testcase', 'batchCreate')))
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

    if($from == 'project') common::printIcon('task', 'create', "project=$param&storyID=$story->id&moduleID=$story->module", $story, 'button', 'smile', '', 'showinonlybody');

    echo "<div class='divider'></div>";
    common::printIcon('story', 'edit', "storyID=$story->id", $story);
    common::printIcon('story', 'create', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id", $story, 'button', 'copy', '', 'iframe showinonlybody', true, "data-width='1050'");
    common::printIcon('story', 'delete', "storyID=$story->id", $story, 'button', '', 'hiddenwin');
    ?>
    <?php endif;?>
  </div>
</div>
<?php
js::set('canCreate', common::hasPriv('story', 'story'));
js::set('createStory', $lang->story->create);
js::set('productID', $story->product);
js::set('branch', $story->branch);
js::set('moduleID', $story->module);
?>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
