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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['story']);?> <strong><?php echo $story->id;?></strong></span>
    <strong style='color: <?php echo $story->color; ?>'><?php echo $story->title;?></strong>
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
  </div>
  <div class='actions'>
    <?php
    $browseLink  = $app->session->storyList != false ? $app->session->storyList : $this->createLink('product', 'browse', "productID=$story->product&branch=$story->branch&moduleID=$story->module");
    $actionLinks = '';

    if(!$story->deleted)
    {
        ob_start();

        echo "<div class='btn-group'>";
        common::printIcon('story', 'change',     "storyID=$story->id", $story);
        common::printIcon('story', 'review',     "storyID=$story->id", $story);

        if($story->status != 'closed' and !isonlybody())
        {
            $misc = "class='btn' data-toggle='modal' data-type='iframe' data-width='95%'";
            $link = $this->createLink('story', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id", '', true);
            if(common::hasPriv('story', 'batchCreate')) echo html::a($link, "<i class='icon icon-branch'></i> " . $lang->story->subdivide, '', $misc);
        }

        common::printIcon('story', 'close',      "storyID=$story->id", $story, 'button', '', '', 'iframe text-danger', true);
        common::printIcon('story', 'activate',   "storyID=$story->id", $story, 'button', '', '', 'iframe text-success', true);

        if($this->config->global->flow != 'onlyStory' and !isonlybody() and (common::hasPriv('testcase', 'create') or common::hasPriv('testcase', 'batchCreate')))
        {
            $this->app->loadLang('testcase');
            echo "<div class='btn-group'>";
            echo "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>";
            echo "<i class='icon icon-sitemap'></i>" . $lang->testcase->common . " <span class='caret'></span>";
            echo "</button>";
            echo "<ul class='dropdown-menu' id='createCaseActionMenu'>";
            $misc = "data-toggle='modal' data-type='iframe' data-width='95%'";
            $link = $this->createLink('testcase', 'create', "productID=$story->product&branch=$story->branch&moduleID=0&from=&param=0&storyID=$story->id", '', true);
            if(common::hasPriv('testcase', 'create')) echo "<li>" . html::a($link, $lang->testcase->create, '', $misc) . "</li>";
            $misc = "data-toggle='modal' data-type='iframe' data-width='95%'";
            $link = $this->createLink('testcase', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=0&storyID=$story->id", '', true);
            if(common::hasPriv('testcase', 'batchCreate')) echo "<li>" . html::a($link, $lang->testcase->batchCreate, '', $misc) . "</li>";
            echo "</ul>";
            echo "</div>";
        }

        if($from == 'project') common::printIcon('task', 'create', "project=$param&storyID=$story->id&moduleID=$story->module", '', 'button', 'smile');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printIcon('story', 'edit', "storyID=$story->id");
        common::printCommentIcon('story');
        common::printIcon('story', 'create', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id", '', 'button', 'copy');
        common::printIcon('story', 'delete', "storyID=$story->id", '', 'button', '', 'hiddenwin');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printRPN($browseLink, $preAndNext);
        echo '</div>';

        $actionLinks = ob_get_contents();
        ob_end_clean();
        echo $actionLinks;
    }
    else
    {
        common::printRPN($browseLink);
    }
    ?>
  </div>
</div>

<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <fieldset>
        <legend><?php echo $lang->story->legendSpec;?></legend>
        <div class='article-content'><?php echo $story->spec;?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->story->legendVerify;?></legend>
        <div class='article-content'><?php echo $story->verify;?></div>
      </fieldset>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $story->files, 'fieldset' => 'true'));?>
      <?php include '../../common/view/action.html.php';?>
      <div class='actions'>
        <?php if(!$story->deleted) echo $actionLinks;?>
      </div>
      <fieldset id='commentBox' class='hide'>
        <legend><?php echo $lang->comment;?></legend>
        <form method='post' action='<?php echo inlink('edit', "storyID=$story->id")?>'>
          <div class="form-group"><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></div>
          <?php echo html::submitButton() . html::backButton();?>
        </form>
      </fieldset>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendBasicInfo' data-toggle='tab'><?php echo $lang->story->legendBasicInfo;?></a></li>
          <li><a href='#legendLifeTime' data-toggle='tab'><?php echo $lang->story->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendBasicInfo'>
            <table class='table table-data table-condensed table-borderless'>
              <tr>
                <th class='w-70px'><?php echo $lang->story->product;?></th>
                <td><?php common::printLink('product', 'view', "productID=$story->product", $product->name);?></td>
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
                <td id='source'><?php echo $lang->story->sourceList[$story->source] . ' ' . $story->sourceNote;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->status;?></th>
                <td class='story-<?php echo $story->status?>'><?php echo $lang->story->statusList[$story->status];?></td>
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
                <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri)?></span></td>
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
            </table>
          </div>
          <div class='tab-pane' id='legendLifeTime'>
            <table class='table table-data table-condensed table-borderless'>
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
            </table>
          </div>

        </div>
      </div>
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
            <ul class='list-unstyled'>
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
                foreach($story->projects as $project)
                {
                    echo "<li title='$project->name'>" . $project->name . '</li>';
                }
            }
            ?>
            </ul>
          </div>
          <?php endif;?>
          <div class="tab-pane <?php if($config->global->flow == 'onlyStory') echo 'active';?>" id='legendRelated'>
            <table class='table table-data table-condensed table-borderless'>
            <?php if($config->global->flow != 'onlyStory'):?>
            <?php if(!empty($fromBug)):?>
              <tr class='text-top'>
                <th class='w-70px'><?php echo $lang->story->legendFromBug;?></th>
                <td class='pd-0'>
                  <ul class='list-unstyled'>
                  <?php echo "<li title='#$fromBug->id $fromBug->title'>" . html::a($this->createLink('bug', 'view', "bugID=$fromBug->id"), "#$fromBug->id $fromBug->title") . '</li>';?>
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
                      echo "<li title='#$bug->id $bug->title'>" . html::a($this->createLink('bug', 'view', "bugID=$bug->id"), "#$bug->id $bug->title") . '</li>';
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
                      echo "<li title='#$case->id $case->title'>" . html::a($this->createLink('testcase', 'view', "caseID=$case->id"), "#$case->id $case->title") . '</li>';
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
                        if(isset($story->extraStories[$linkStoryID])) echo '<li>' . html::a(inlink('view', "storyID=$linkStoryID"), "#$linkStoryID " . $story->extraStories[$linkStoryID]) . '</li>';
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
                      if(isset($story->extraStories[$childStoryID])) echo '<li>' . html::a(inlink('view', "storyID=$childStoryID"), "#$childStoryID " . $story->extraStories[$childStoryID]) . '</li>';
                    }
                    ?>
                  </ul>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
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
