<div class='tabs'>
  <ul class='nav nav-tabs'>
    <li class='active'><a data-toggle='tab' href='#affectedProjects'><?php echo $lang->story->affectedProjects;?> <?php $count = count($story->projects); if($count > 0) echo "<span class='label label-danger label-badge label-circle'>" . $count . "</span>" ?></a></li>
    <li><a data-toggle='tab' href='#affectedBugs'><?php echo $lang->story->affectedBugs;?> <?php $count = count($story->bugs); if($count > 0) echo "<span class='label label-danger label-badge label-circle'>" . $count . "</span>" ?></a></li>
    <li><a data-toggle='tab' href='#affectedCases'><?php echo $lang->story->affectedCases;?> <?php $count = count($story->cases); if($count > 0) echo "<span class='label label-danger label-badge label-circle'>" . $count . "</span>" ?></a></li>
  </ul>
  <div class='tab-content'>
    <div class='tab-pane active' id='affectedProjects'>
      <?php foreach($story->projects as $projectID => $project):?>
        <h6><?php echo html::icon($lang->icons['project'], 'icon');?> <strong><?php echo $project->name ?></strong> &nbsp; <small><i class='icon-group'></i> <?php foreach($story->teams[$projectID] as $member) echo zget($users, $member->account, $member->account) . ' ';?></small></h6>
          <table class='table table-borderless table-condensed'>
            <thead>
             <tr>
              <th><?php echo $lang->task->id;?></th>
              <th><?php echo $lang->task->name;?></th>
              <th><?php echo $lang->task->assignedTo;?></th>
              <th><?php echo $lang->task->status;?></th>
              <th><?php echo $lang->task->consumed;?></th>
              <th><?php echo $lang->task->left;?></th>
            </tr>
            </thead>
            <?php if(isset($story->tasks[$projectID])):?>
            <tbody class='<?php if(count($story->tasks[$projectID]) > $config->story->affectedFixedNum)  echo "linkbox";?>'>
            <?php foreach($story->tasks[$projectID] as $task):?> 
              <tr class='text-center'>
                <td><?php echo $task->id;?></td>
                <td class='text-left'><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name, '_blank');?></td>
                <td><?php echo $users[$task->assignedTo];?></td>
                <td class='task-<?php echo $task->status?>'><?php echo $lang->task->statusList[$task->status];?></td>
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
      <table class='table table-borderless table-condensed'>
        <thead>
          <tr>
            <th class='w-60px'><?php echo $lang->bug->id;?></th>
            <th><?php echo $lang->bug->title;?></th>
            <th class='w-60px'><?php echo $lang->bug->status;?></th>
            <th class='w-70px'><?php echo $lang->bug->openedBy;?></th>
            <th><?php echo $lang->bug->resolvedBy;?></th>
            <th><?php echo $lang->bug->resolution;?></th>
            <th><?php echo $lang->bug->lastEditedBy;?></th>
          </tr>
        </thead>
        <tbody class= '<?php if(count($story->bugs) > $config->story->affectedFixedNum) echo "linkbox";?>'>
          <?php foreach($story->bugs as $bug):?>
          <tr class='text-center'>
            <td><?php echo $bug->id;?></td>
            <td class='text-left'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, '_blank');?></td>
            <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status];?></td>
            <td><?php echo $users[$bug->openedBy];?></td>
            <td><?php echo $users[$bug->resolvedBy];?></td>
            <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
            <td><?php echo $users[$bug->lastEditedBy];?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <div class='tab-pane' id='affectedCases'>
      <table class='table table-borderless table-condensed'>
        <thead>
          <tr>
            <th class='w-70px'><?php echo $lang->testcase->id;?></th>
            <th><?php echo $lang->testcase->title;?></th>
            <th class='w-70px'><?php echo $lang->testcase->status;?></th>
            <th class='w-70px'><?php echo $lang->testcase->openedBy;?></th>
            <th><?php echo $lang->testcase->lastEditedBy;?></th>
          </tr>
        </thead>
        <tbody class='<?php if(count($story->cases) > $config->story->affectedFixedNum)  echo "linkbox";?>'>
        <?php foreach($story->cases as $case):?>
          <tr class='text-center'>
            <td><?php echo $case->id;?></td>
            <td class='text-left'><?php echo html::a($this->createLink('testcase', 'view', "caseID=$case->id"), $case->title, '_blank');?></td>
            <td class='case-<?php echo $case->status?>'><?php echo $lang->testcase->statusList[$case->status];?></td>
            <td><?php echo $users[$case->openedBy];?></td>
            <td><?php echo $users[$case->lastEditedBy];?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>
