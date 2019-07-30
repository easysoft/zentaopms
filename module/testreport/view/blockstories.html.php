<?php $sysURL = $this->session->notHead ? common::getSysURL() : '';?>
<table class='table main-table' id='stories'>
  <thead>
    <tr>
      <th class='w-id'>  <?php echo $lang->idAB;?></th>
      <th class='w-pri'> <?php echo $lang->priAB;?></th>
      <th class='text-left'><?php echo $lang->testreport->storyTitle;?></th>
      <th class='w-user'><?php echo $lang->openedByAB;?></th>
      <th class='w-80px'><?php echo $lang->assignedToAB;?></th>
      <th class='w-hour'><?php echo $lang->story->estimateAB;?></th>
      <th class='w-80px'><?php echo $lang->statusAB;?></th>
      <th class='w-80px'><?php echo $lang->story->stageAB;?></th>
    </tr>
  </thead>
  <?php if($stories):?>
  <tbody>
    <?php foreach($stories as $story):?>
    <tr>
      <td><?php echo sprintf('%03d', $story->id) . html::hidden('stories[]', $story->id)?></td>
      <td><span class='label-pri lable-pri-<?php echo $story->pri?>' title='<?php echo zget($lang->story->priList, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri);?></span></td>
      <td class='text-left' title='<?php echo $story->title?>'><?php echo html::a($sysURL . $this->createLink('story', 'view', "storyID=$story->id", '', true), $story->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
      <td><?php echo zget($users, $story->openedBy);?></td>
      <td><?php echo zget($users, $story->assignedTo);?></td>
      <td><?php echo $story->estimate?></td>
      <?php $status = $this->processStatus('story', $story);?>
      <td title='<?php echo $status;?>'>
        <span class="status-story status-<?php echo $story->status?>"><?php echo $status;?></span>
      </td>
      <td><?php echo zget($lang->story->stageList, $story->stage);?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
  <?php else:?>
  <tr><td class='none-data' colspan='8'><?php echo $lang->testreport->none;?></td></tr>
  <?php endif?>
</table>
