<?php $sysURL = $this->session->notHead ? common::getSysURL() : '';?>
<table class='table main-table' id='bugs'>
  <thead>
    <tr>
      <th class='w-id'>   <?php echo $lang->idAB;?></th>
      <th class='w-pri'>  <?php echo $lang->priAB;?></th>
      <th class='text-left'><?php echo $lang->testreport->bugTitle;?></th>
      <th class='w-user'> <?php echo $lang->openedByAB;?></th>
      <th class='w-user'><?php echo $lang->bug->resolvedBy;?></th>
      <th class='w-140px'><?php echo $lang->bug->resolvedDate;?></th>
      <th class='w-80px'> <?php echo $lang->statusAB;?></th>
    </tr>
  </thead>
<?php if($bugs):?>
  <tbody>
    <?php foreach($bugs as $bug):?>
    <tr>
      <td><?php echo sprintf('%03d', $bug->id) . html::hidden('bugs[]', $bug->id)?></td>
      <td><span class='label-pri label-pri-<?php echo $bug->pri?>' title='<?php echo zget($lang->bug->priList, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri);?></span></td>
      <td class='text-left' title='<?php echo $bug->title?>'><?php echo html::a($sysURL . $this->createLink('bug', 'view', "bugID=$bug->id", '', true), $bug->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
      <td><?php echo zget($users, $bug->openedBy);?></td>
      <td><?php echo zget($users, $bug->resolvedBy);?></td>
      <td><?php if($bug->resolvedDate != '0000-00-00 00:00:00') echo substr($bug->resolvedDate, 2);?></td>
      <td title='<?php echo zget($lang->bug->statusList, $bug->status);?>'>
        <span class="status-<?php echo $bug->status?>">
          <span class="label label-dot"></span>
          <span class='status-text'><?php echo zget($lang->bug->statusList, $bug->status);?></span>
        </span>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
  <?php else:?>
  <tr><td class='none-data' colspan='7'><?php echo $lang->testreport->none;?></td></tr>
  <?php endif?>
</table>
