<div style='<?php if(count($story->projects)==0) echo "display:none";?>'>
  <table class='table-1 mt-10px'>
    <caption><?php echo $lang->story->affectedProjects;?></caption>

    <?php foreach($story->projects as $projectID => $project):?>
    <tr>
      <td class='a-center'><?php echo "<strong>$project->name</strong><br />"; ?> </td>
      <td class='a-center'><?php foreach($story->teams[$projectID] as $member) echo $users[$member->account] . ' ';?></td>
      <td>
        <table class='table-1' style='margin:0'>
          <tr class='colhead'>
            <th><?php echo $lang->task->id;?></th>
            <th><?php echo $lang->task->name;?></th>
            <th><?php echo $lang->task->assignedTo;?></th>
            <th><?php echo $lang->task->status;?></th>
            <th><?php echo $lang->task->consumed;?></th>
            <th><?php echo $lang->task->left;?></th>
          </tr>
        </table>
        <?php if(isset($story->tasks[$projectID])):?>
        <div class='<?php if(count($story->tasks[$projectID]) > 5)  echo "linkbox";?>'>
          <table class='table-1'>
          <?php foreach($story->tasks[$projectID] as $task):?> 
            <tr class='a-center'>
              <td><?php echo $task->id;?></td>
              <td class='a-left'><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name, '_blank');?></td>
              <td><?php echo $users[$task->assignedTo];?></td>
              <td><?php echo $lang->task->statusList[$task->status];?></td>
              <td><?php echo $task->consumed;?></td>
              <td><?php echo $task->left;?></td>
            </tr>
            <?php endforeach;?>
          </table>
        </div>
        <?php endif;?>
      </td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<div style='<?php if(count($story->bugs)==0) echo "display:none";?>'>
  <table class='table-1'>
    <caption><?php echo $lang->story->affectedBugs;?></caption>
      <tr>
        <th class='w-p10'><?php echo $lang->bug->id;?></th>
        <th class='w-p40'><?php echo $lang->bug->title;?></th>
        <th class='w-p10'><?php echo $lang->bug->status;?></th>
        <th class='w-p10'><?php echo $lang->bug->openedBy;?></th>
        <th><?php echo $lang->bug->resolvedBy;?></th>
        <th><?php echo $lang->bug->resolution;?></th>
        <th><?php echo $lang->bug->lastEditedBy;?></th>
      </tr>
  </table>
  <div class= '<?php if(count($story->bugs) > 7) echo "linkbox";?>'>
    <table class='table-1'>
    <?php foreach($story->bugs as $bug):?>
      <tr class='a-center'>
        <td class='w-p10'><?php echo $bug->id;?></td>
        <td class='w-p40'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, '_blank');?></td>
        <td class='w-p10'><?php echo $lang->bug->statusList[$bug->status];?></td>
        <td class='w-p10'><?php echo $users[$bug->openedBy];?></td>
        <td><?php echo $users[$bug->resolvedBy];?></td>
        <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
        <td><?php echo $users[$bug->lastEditedBy];?></td>
      </tr>
      <?php endforeach;?>
    </table>
  </div>
</div>
<div style='<?php if(count($story->cases)==0) echo "display:none";?>'>
  <table class='table-1'>
    <caption><?php echo $lang->story->affectedCases;?></caption>
      <tr>
        <th class='w-p10'><?php echo $lang->testcase->id;?></th>
        <th class='w-p40'><?php echo $lang->testcase->title;?></th>
        <th class='w-p10'><?php echo $lang->testcase->status;?></th>
        <th class='w-p10'><?php echo $lang->testcase->openedBy;?></th>
        <th><?php echo $lang->testcase->lastEditedBy;?></th>
      </tr>
  </table>
  <div class='<?php if(count($story->cases) > 7)  echo "linkbox";?>'>
    <table class='table-1'>
    <?php foreach($story->cases as $case):?>
      <tr class='a-center'>
        <td class='w-p10'><?php echo $case->id;?></td>
        <td class='w-p40'><?php echo html::a($this->createLink('testcase', 'view', "caseID=$case->id"), $case->title, '_blank');?></td>
        <td class='w-p10'><?php echo $lang->testcase->statusList[$case->status];?></td>
        <td class='w-p10'><?php echo $users[$case->openedBy];?></td>
        <td><?php echo $users[$case->lastEditedBy];?></td>
      </tr>
      <?php endforeach;?>
    </table>
  </div>
</div>
