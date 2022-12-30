<div id='commit-box' class='open'>
  <div class='dropdown-menu commit-show'>
    <div class='commit-title'><?php echo $lang->repo->commitInfo;?></div>
    <table>
      <tr class='empty'>
        <th><?php echo $lang->repo->revisionA;?></th>
        <td><?php echo html::a($this->repo->createLink('revision', "repoID=$repoID&objectID=0&revision={$commit->revision}"), substr($commit->revision, 0, 10));?></td>
      </tr>
      <tr class='empty'>
        <th><?php echo $lang->repo->committer;?></th>
        <td><?php echo $commit->committer;?></td>
      </tr>
      <tr class='empty'>
        <th><?php echo $lang->repo->comment;?></th>
        <td title='<?php echo $commit->comment;?>'><?php echo $commit->comment;?></td>
      </tr>
      <tr class='empty'>
        <th><?php echo $lang->repo->time;?></th>
        <td><?php echo $commit->time;?></td>
      </tr>
      <?php $storyIndex = $taskIndex = $bugIndex = 1;?>
      <?php $canViewStory = common::hasPriv('story', 'view');?>
      <?php $canViewTask  = common::hasPriv('task',  'view');?>
      <?php $canViewBug   = common::hasPriv('bug',   'view');?>
      <?php foreach($stories as $storyID => $storyTitle):?>
      <tr class='empty'>
        <th><?php echo $storyIndex == 1 ? $lang->repo->linkedStory : '';?></th>
        <td><?php echo $canViewStory ? html::a($this->createLink('story', 'view', "storyID=$storyID"), "#$storyID $storyTitle") : "#$storyID $storyTitle";?></td>
      </tr>
      <?php $storyIndex++;?>
      <?php endforeach;?>
      <?php foreach($tasks as $taskID => $taskName):?>
      <tr class='empty'>
        <th><?php echo $taskIndex == 1 ? $lang->repo->linkedTask : '';?></th>
        <td><?php echo $canViewTask ? html::a($this->createLink('task', 'view', "taskID=$taskID"), "#$taskID $taskName") : "#$taskID $taskName";?></td>
      </tr>
      <?php $taskIndex++;?>
      <?php endforeach;?>
      <?php foreach($bugs as $bugID => $bugTitle):?>
      <tr class='empty'>
        <th><?php echo $bugIndex == 1 ? $lang->repo->linkedBug : '';?></th>
        <td><?php echo $canViewBug ? html::a($this->createLink('bug', 'view', "bugID=$bugID"), "#$bugID $bugTitle") : "#$bugID $bugTitle";?></td>
      </tr>
      <?php $bugIndex++;?>
      <?php endforeach;?>
    </table>
  </div>
</div>
