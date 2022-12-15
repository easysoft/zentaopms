<?php
if(isset($twins))
{
   $twinsClass       = isonlybody() ? 'showinonlybody' : 'iframe';
   $canViewLinkStory = common::hasPriv('story', 'view');
   $lang->story->currentBranch = sprintf($this->lang->story->currentBranch, $this->lang->product->branchName[$product->type]);
   unset($twins[$story->id]);
}?>
<div class='tabs'>
  <ul class='nav nav-tabs'>
    <li class='active'><a data-toggle='tab' href='#affectedProjects'><?php echo $lang->story->affectedProjects;?> <?php $count = count($story->executions); if($count > 0) echo "<span class='label label-danger label-badge label-circle'>" . $count . "</span>" ?></a></li>
    <li><a data-toggle='tab' href='#affectedBugs'><?php echo $lang->story->affectedBugs;?> <?php $count = count($story->bugs); if($count > 0) echo "<span class='label label-danger label-badge label-circle'>" . $count . "</span>" ?></a></li>
    <li><a data-toggle='tab' href='#affectedCases'><?php echo $lang->story->affectedCases;?> <?php $count = count($story->cases); if($count > 0) echo "<span class='label label-danger label-badge label-circle'>" . $count . "</span>" ?></a></li>
    <li><a data-toggle='tab' href='#affectedTwins'><?php if(isset($twins) and count($twins) > 0) echo $lang->story->affectedTwins;?> <?php if(isset($twins) and count($twins) > 0) echo "<span class='label label-danger label-badge label-circle'>" . count($twins) . "</span>" ?></a></li>
  </ul>
  <div class='tab-content'>
    <div class='tab-pane active' id='affectedProjects'>
      <?php foreach($story->executions as $executionID => $execution):?>
        <h6><?php echo $execution->name ?> &nbsp;
            <?php if(!empty($story->teams[$executionID])):?>
            <small><i class='icon-group'></i> <?php foreach($story->teams[$executionID] as $member) echo zget($users, $member->account) . ' ';?></small>
            <?php endif;?>
        </h6>
          <table class='table'>
            <thead>
              <tr class='text-center'>
                <th class='c-id'><?php echo $lang->task->id;?></th>
                <th class='text-left'><?php echo $lang->task->name;?></th>
                <th class='c-user'><?php echo $lang->task->assignedTo;?></th>
                <th class='c-status'><?php echo $lang->task->status;?></th>
                <th class='c-consumed'><?php echo $lang->task->consumed;?></th>
                <th class='c-left'><?php echo $lang->task->left;?></th>
              </tr>
            </thead>
            <?php if(isset($story->tasks[$executionID])):?>
            <tbody class='<?php if(count($story->tasks[$executionID]) > $config->story->affectedFixedNum)  echo "linkbox";?>'>
            <?php foreach($story->tasks[$executionID] as $task):?>
              <tr class='text-center'>
                <td><?php echo $task->id;?></td>
                <td class='text-left'><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name, '_blank');?></td>
                <td><?php echo zget($users, $task->assignedTo);?></td>
                <td>
                  <span class='status-task status-<?php echo $task->status?>'><?php echo $this->processStatus('task', $task);?></span>
                </td>
                <td><?php echo $task->consumed;?></td>
                <td><?php echo $task->left;?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
            <?php endif;?>
          </table>
      <?php endforeach;?>
    </div>
    <div class='tab-pane' id='affectedBugs'>
      <table class='table'>
        <thead>
          <tr class='text-center'>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='text-left'><?php echo $lang->bug->title;?></th>
            <th class='c-status'><?php echo $lang->statusAB;?></th>
            <th class='c-user'><?php echo $lang->bug->openedBy;?></th>
            <th class='c-user'><?php echo $lang->bug->resolvedBy;?></th>
            <th class='text-left'><?php echo $lang->bug->resolution;?></th>
            <th class='c-user'><?php echo $lang->bug->lastEditedBy;?></th>
          </tr>
        </thead>
        <tbody class= '<?php if(count($story->bugs) > $config->story->affectedFixedNum) echo "linkbox";?>'>
          <?php foreach($story->bugs as $bug):?>
          <tr class='text-center'>
            <td><?php echo $bug->id;?></td>
            <td class='text-left'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, '_blank');?></td>
            <td>
              <span class='status-bug status-<?php echo $bug->status?>'><?php echo $this->processStatus('bug', $bug);?></span>
            </td>
            <td><?php echo zget($users, $bug->openedBy);?></td>
            <td><?php echo zget($users, $bug->resolvedBy);?></td>
            <td class='text-left'><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
            <td><?php echo zget($users, $bug->lastEditedBy);?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <div class='tab-pane' id='affectedCases'>
      <table class='table'>
        <thead>
          <tr class='text-center'>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='text-left'><?php echo $lang->testcase->title;?></th>
            <th class='c-status'><?php echo $lang->statusAB;?></th>
            <th class='c-user'><?php echo $lang->testcase->openedBy;?></th>
            <th class='c-user'><?php echo $lang->testcase->lastEditedBy;?></th>
          </tr>
        </thead>
        <tbody class='<?php if(count($story->cases) > $config->story->affectedFixedNum)  echo "linkbox";?>'>
        <?php foreach($story->cases as $case):?>
          <tr class='text-center'>
            <td><?php echo $case->id;?></td>
            <td class='text-left'><?php echo html::a($this->createLink('testcase', 'view', "caseID=$case->id"), $case->title, '_blank');?></td>
            <td>
              <span class='status-case status-<?php echo $case->status?>'><?php echo $this->processStatus('testcase', $case);?></span>
            </td>
            <td><?php echo zget($users, $case->openedBy);?></td>
            <td><?php echo zget($users, $case->lastEditedBy);?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <?php if(isset($twins)):?>
    <div class='tab-pane' id='affectedTwins'>
      <table class='table'>
        <thead>
          <tr class='text-center'>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='c-branch'><?php echo $lang->story->branch;?></th>
            <th class='text-left'><?php echo $lang->story->title;?></th>
            <th class='c-status'><?php echo $lang->statusAB;?></th>
            <th class='c-stage'><?php echo $lang->story->stageAB;?></th>
            <th class='c-user'><?php echo $lang->story->openedBy;?></th>
            <th class='c-user'><?php echo $lang->story->lastEditedByAB;?></th>
          </tr>
        </thead>
        <tbody class='<?php if(count($twins) > $config->story->affectedFixedNum)  echo "linkbox";?>'>
          <?php foreach($twins as $twin):?>
          <?php
              $branch = isset($branches[$twin->branch]) ? $branches[$twin->branch] : '';
              $labelClass = $story->branch == $twin->branch ? 'label-primary' : '';
          ?>
          <tr class='text-center'>
            <td><?php echo $twin->id;?></td>
            <td><span class='label <?php echo $labelClass;?> label-outline label-badge' title='<?php echo $branch;?>'><?php echo $branch;?></span></td>
            <td class='text-left'><?php echo ($canViewLinkStory ? html::a($this->createLink('story', 'view', "id=$twin->id", '', true), "$twin->title", '', "class='$twinsClass viewlink'") : "$twin->title");?></td>
            <td><span class='status-twins status-<?php echo $twin->status?>'><?php echo $this->processStatus('story', $twin);?></span></td>
            <td><?php echo $lang->story->stageList[$twin->stage];?></td>
            <td><?php echo zget($users, $twin->openedBy);?></td>
            <td><?php echo zget($users, $twin->lastEditedBy);?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <?php endif;?>
  </div>
</div>
