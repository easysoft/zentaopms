<?php
/**
 * The view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
    <strong><?php echo $story->title;?></strong>
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
    $browseLink  = $app->session->storyList != false ? $app->session->storyList : $this->createLink('product', 'browse', "productID=$story->product&moduleID=$story->module");
    $actionLinks = '';
    if(!$story->deleted)
    {
        ob_start();

        echo "<div class='btn-group'>";
        common::printIcon('story', 'change',     "storyID=$story->id", $story);
        common::printIcon('story', 'review',     "storyID=$story->id", $story);
        common::printIcon('story', 'close',      "storyID=$story->id", $story, 'button', '', '', 'iframe text-danger', true);
        common::printIcon('story', 'activate',   "storyID=$story->id", $story, 'button', '', '', 'iframe text-success', true);
        common::printIcon('story', 'createCase', "productID=$story->product&moduleID=0&from=&param=0&storyID=$story->id", '', 'button', 'sitemap');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printIcon('story', 'edit', "storyID=$story->id");
        common::printCommentIcon('story');
        common::printIcon('story', 'create', "productID=$story->product&moduleID=$story->module&storyID=$story->id", '', 'button', 'copy');
        common::printIcon('story', 'delete', "storyID=$story->id", '', 'button', '', 'hiddenwin');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printRPN($browseLink, $preAndNext);
        echo '</div>';

        $actionLinks = ob_get_contents();
        ob_clean();
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
              <tr>
                <th><?php echo $lang->story->module;?></th>
                <td> 
                <?php
                if(empty($modulePath))
                {
                    echo "/";
                }
                else
                {
                    foreach($modulePath as $key => $module)
                    {
                        if(!common::printLink('product', 'browse', "productID=$story->product&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
                        if(isset($modulePath[$key + 1])) echo $lang->arrow;
                    }
                }
                ?>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->story->plan;?></th>
                <td><?php if(isset($story->planTitle)) if(!common::printLink('productplan', 'view', "planID=$story->plan", $story->planTitle)) echo $story->planTitle;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->source;?></th>
                <td><?php echo $lang->story->sourceList[$story->source];?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->status;?></th>
                <td class='story-<?php echo $story->status?>'><?php echo $lang->story->statusList[$story->status];?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->stage;?></th>
                <td><?php echo $lang->story->stageList[$story->stage];?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->pri;?></th>
                <td><?php echo $lang->story->priList[$story->pri];?></td>
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
          <li class='active'><a href='#legendProjectAndTask' data-toggle='tab'><?php echo $lang->story->legendProjectAndTask;?></a></li>
          <li><a href='#legendRelated' data-toggle='tab'><?php echo $lang->story->legendRelated;?></a></li>
        </ul>
        <div class='tab-content'>
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
          <div class='tab-pane' id='legendRelated'>
            <table class='table table-data table-condensed table-borderless'>
              <tr class='text-top'>
                <th class='w-70px'><?php echo $lang->story->legendBugs;?></th>
                <td class='pd-0'>
                  <ul class='list-unstyled'>
                  <?php
                  if(!empty($fromBug)) echo "<li title='#$fromBug->id $fromBug->title'>" . html::a($this->createLink('bug', 'view', "bugID=$fromBug->id"), "#$fromBug->id $fromBug->title") . " <span class='label label-warning'>{$lang->story->legendFromBug}</span></li>";
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
              <tr class='text-top'>
                <th><?php echo $lang->story->legendLinkStories;?></th>
                <td class='pd-0'>
                  <ul class='list-unstyled'>
                    <?php
                    $linkStories = explode(',', $story->linkStories) ;    
                    foreach($linkStories as $linkStoryID)
                    {
                        if(isset($story->extraStories[$linkStoryID])) echo '<li>' . html::a(inlink('view', "storyID=$linkStoryID"), "#$linkStoryID " . $story->extraStories[$linkStoryID]) . '</li>';
                    }
                    $childStories = explode(',', $story->childStories) ;    
                    foreach($childStories as $childStoryID)
                    {
                      if(isset($story->extraStories[$childStoryID])) echo '<li>' . html::a(inlink('view', "storyID=$childStoryID"), "#$childStoryID " . $story->extraStories[$childStoryID]) . " <span class='label label-info'>{$lang->story->legendChildStories}</span></li>";
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
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
