<?php if(isset($pageCSS)) css::internal($pageCSS);?>
<div class="detail">
  <h2 class="detail-title"><span class="label-id"><?php echo $story->id?></span> <span class="label label-story"><?php echo $lang->story->common?></span> <span class="title"><?php echo $story->title;?></span></h2>
  <div class="detail-content article-content">
    <div class="infos">
      <span class="status-story status-draft"><span class="label label-dot"></span> <?php echo $this->processStatus('story', $story);?></span>
      <span><?php echo $lang->story->stage;?> <?php echo $lang->story->stageList[$story->stage];?></span>
      <span><?php echo $lang->story->estimate;?> <?php echo $story->estimate;?></span>
    </div>
    <div class="btn-toolbar">
      <?php
      $vars = "story={$story->id}";
      common::printIcon('story', 'change', $vars, $story, 'list', 'fork', '', 'btn btn-info btn-icon');
      common::printIcon('story', 'delete', $vars, $story, 'list', 'trash', 'hiddenwin', 'btn btn-info btn-icon');
      common::printIcon('story', 'review', $vars, $story, 'list', 'glasses', '', 'btn btn-info btn-icon');
      common::printIcon('story', 'close',  $vars, $story, 'list', 'off', '', 'btn btn-info btn-icon iframe', true);
      common::printIcon('story', 'edit',   $vars, $story, 'list', '', '', 'btn btn-info btn-icon');
      if($config->global->flow != 'onlyStory') common::printIcon('story', 'createCase', "productID=$story->product&branch=$story->branch&module=0&from=&param=0&$vars", $story, 'list', 'sitemap', '', 'btn btn-info btn-icon');
      ?>
    </div>
  </div>
</div>
<div class="detail">
  <div class="detail-title"><?php echo $lang->story->legendSpec;?></div>
  <div class="detail-content article-content">
    <?php echo !empty($story->spec) ? $story->spec : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
  </div>
</div>
<div class="detail">
  <div class="detail-title"><?php echo $lang->story->legendVerify;?></div>
  <div class="detail-content article-content">
    <?php echo !empty($story->verify) ? $story->verify : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
  </div>
</div>
<?php echo $this->fetch('file', 'printFiles', array('files' => $story->files, 'fieldset' => 'true'));?>

<details class="detail" open>
  <summary class="detail-title"><?php echo $lang->story->legendBasicInfo;?></summary>
  <div class="detail-content">
    <table class="table table-data">
      <tbody>
      <tr>
        <th class='w-100px'><?php echo $lang->story->product;?></th>
        <td><?php echo html::a($this->createLink('product', 'view', "productID=$story->product"), $product->name);?></td>
      </tr>
      <?php if($product->type != 'normal'):?>
        <tr>
          <th><?php echo sprintf($lang->product->branch, zget($lang->product->branchName, $product->type));?></th>
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
          else
          {
              echo $lang->noData;
          }
          ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->story->source;?></th>
        <td id='source'><?php echo $story->source ? $lang->story->sourceList[$story->source] : $lang->noData;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->sourceNote;?></th>
        <td><?php echo $story->sourceNote ? $story->sourceNote : $lang->noData;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->status;?></th>
        <td><span class='status-<?php echo $story->status?>'><span class="label label-dot"></span> <?php echo $this->processStatus('story', $story);?></span></td>
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
        <td>
          <?php if($story->pri):?>
          <span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri)?></span>
          <?php else:?>
          <?php echo $lang->noData;?>
          <?php endif;?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->story->estimate;?></th>
        <td><?php echo $story->estimate;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->keywords;?></th>
        <td><?php echo $story->keywords ? $story->keywords : $lang->noData;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->legendMailto;?></th>
        <td>
          <?php
          $mailto = explode(',', $story->mailto);
          if(empty($mainto))
          {
              echo $lang->noData;
          }
          else
          {
              foreach($mailto as $account)
              {
                  if(empty($account)) continue; echo "<span>" . zget($users, trim($account)) . '</span> &nbsp;';
              }
          }
          ?>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</details>

<details class="detail" open>
  <summary class="detail-title"><?php echo $lang->story->legendProjectAndTask;?></summary>
  <div class="detail-content">
    <ul class="list-unstyled">
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

<details class="detail" open>
  <summary class="detail-title"><?php echo $lang->story->legendRelated;?></summary>
  <div class="detail-content">
    <table class="table table-data">
      <tbody>
        <?php if($config->global->flow != 'onlyStory'):?>
        <?php if(!empty($fromBug)):?>
        <tr class='text-top'>
          <th class='thWidth'><?php echo $lang->story->legendFromBug;?></th>
          <td class='pd-0'>
            <ul class='list-unstyled'>
                <?php echo "<li title='#$fromBug->id $fromBug->title'>" . html::a($this->createLink('bug', 'view', "bugID=$fromBug->id"), "#$fromBug->id $fromBug->title") . '</li>';?>
            </ul>
          </td>
        </tr>
        <?php endif;?>
        <tr class='text-top'>
          <th class='thWidth'><?php echo $lang->story->legendBugs;?></th>
          <td class='pd-0'>
            <?php if(empty($bugs)):?>
            <?php echo $lang->noData;?>
            <?php else:?>
            <ul class='list-unstyled'>
              <?php
              foreach($bugs as $bug)
              {
                  echo "<li title='#$bug->id $bug->title'>" . html::a($this->createLink('bug', 'view', "bugID=$bug->id"), "#$bug->id $bug->title") . '</li>';
              }
              ?>
            </ul>
            <?php endif;?>
          </td>
        </tr>
        <tr class='text-top'>
          <th><?php echo $lang->story->legendCases;?></th>
          <td class='pd-0'>
            <?php if(empty($cases)):?>
            <?php echo $lang->noData;?>
            <?php else:?>
            <ul class='list-unstyled'>
              <?php
              foreach($cases as $case)
              {
                  echo "<li title='#$case->id $case->title'>" . html::a($this->createLink('testcase', 'view', "caseID=$case->id"), "#$case->id $case->title") . '</li>';
              }
              ?>
            </ul>
            <?php endif;?>
          </td>
        </tr>
      <?php endif;?>
      <tr class='text-top'>
        <th><?php echo $lang->story->legendLinkStories;?></th>
        <td class='pd-0'>
          <?php $linkStories = explode(',', $story->linkStories);?>
          <?php if(count($linkStories) < 2):?>
          <?php echo $lang->noData;?>
          <?php else:?>
          <ul class='list-unstyled'>
            <?php
            foreach($linkStories as $linkStoryID)
            {
                if(isset($story->extraStories[$linkStoryID])) echo '<li>' . html::a($this->createLink('story', 'view', "storyID=$linkStoryID"), "#$linkStoryID " . $story->extraStories[$linkStoryID]) . '</li>';
            }
            ?>
          </ul>
          <?php endif;?>
        </td>
      </tr>
      <tr class='text-top'>
        <th><?php echo $lang->story->legendChildStories;?></th>
        <td class='pd-0'>
          <?php $childStories = explode(',', $story->childStories);?>
          <?php if(count($childStories) < 2):?>
          <?php echo $lang->noData;?>
          <?php else:?>
          <ul class='list-unstyled'>
            <?php
            foreach($childStories as $childStoryID)
            {
                if(isset($story->extraStories[$childStoryID])) echo '<li>' . html::a($this->createLink('story', 'view', "storyID=$childStoryID"), "#$childStoryID " . $story->extraStories[$childStoryID]) . '</li>';
            }
            ?>
          </ul>
          <?php endif;?>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</details>

<details class="detail" open>
  <summary class="detail-title"><?php echo $lang->story->legendLifeTime;?></summary>
  <div class="detail-content">
    <table class="table table-data">
      <tbody>
      <tr>
        <th class='w-100px'><?php echo $lang->story->openedBy;?></th>
        <td><?php echo zget($users, $story->openedBy) . $lang->at . $story->openedDate;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->assignedTo;?></th>
        <td><?php echo $story->assignedTo ? zget($users, $story->assignedTo) . $lang->at . $story->assignedDate : $lang->noData;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->reviewedBy;?></th>
        <td>
          <?php
          $reviewedBy = explode(',', $story->reviewedBy);
          if(count($reviewedBy) < 2)
          {
              echo $lang->noData;
          }
          else
          {
              foreach($reviewedBy as $account) echo ' ' . zget($users, trim($account));
          }
          ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->story->reviewedDate;?></th>
        <td><?php echo $story->reviewedBy ? $story->reviewedDate : $lang->noData;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->closedBy;?></th>
        <td><?php echo $story->closedBy ? zget($users, $story->closedBy) . $lang->at . $story->closedDate : $lang->noData;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->closedReason;?></th>
        <td>
          <?php
          echo $story->closedReason ? $lang->story->reasonList[$story->closedReason] : $lang->noData;
          if(isset($story->extraStories[$story->duplicateStory]))
          {
              echo html::a(inlink('view', "storyID=$story->duplicateStory"), '#' . $story->duplicateStory . ' ' . $story->extraStories[$story->duplicateStory]);
          }
          ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->story->lastEditedBy;?></th>
        <td><?php echo $story->lastEditedBy ? zget($users, $story->lastEditedBy) . $lang->at . $story->lastEditedDate : $lang->noData;?></td>
      </tr>
      </tbody>
    </table>
  </div>
</details>
<?php $actionFormLink = $this->createLink('action', 'comment', "objectType=story&objectID=$story->id");?>
<?php include '../../common/view/action.html.php';?>
<script>
<?php if(isset($pageJS)) echo $pageJS;?>
</script>
