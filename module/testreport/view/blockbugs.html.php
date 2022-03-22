<?php $sysURL = $this->session->notHead ? common::getSysURL() : '';?>
<table class='table main-table' id='bugs'>
  <thead>
    <tr>
      <th class='c-id'>       <?php echo $lang->idAB;?></th>
      <th class='c-status'>   <?php echo $lang->priAB;?></th>
      <th class='text-left'>  <?php echo $lang->testreport->bugTitle;?></th>
      <th class='c-user'>     <?php echo $lang->openedByAB;?></th>
      <th class='c-user'>     <?php echo $lang->bug->resolvedBy;?></th>
      <th class='c-full-date'><?php echo $lang->bug->resolvedDate;?></th>
      <th class='c-status'>   <?php echo $lang->statusAB;?></th>
    </tr>
  </thead>
<?php if($bugs):?>
  <tbody>
    <?php foreach($bugs as $bug):?>
    <tr>
      <td><?php echo sprintf('%03d', $bug->id) . html::hidden('bugs[]', $bug->id)?></td>
      <td><span class='label-pri label-pri-<?php echo $bug->pri?>' title='<?php echo zget($lang->bug->priList, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri);?></span></td>
      <td class='text-left c-name' title='<?php echo $bug->title?>'><?php echo html::a($sysURL . $this->createLink('bug', 'view', "bugID=$bug->id", '', true), $bug->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
      <td><?php echo zget($users, $bug->openedBy);?></td>
      <td><?php echo zget($users, $bug->resolvedBy);?></td>
      <td><?php if(!helper::isZeroDate($bug->resolvedDate)) echo substr($bug->resolvedDate, 2);?></td>
      <?php $status = $this->processStatus('bug', $bug);?>
      <td title='<?php echo $status;?>'>
        <span class="status-bug status-<?php echo $bug->status?>"><?php echo $status;?></span>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
  <?php else:?>
  <tr><td class='none-data' colspan='7'><?php echo $lang->testreport->none;?></td></tr>
  <?php endif?>
</table>
