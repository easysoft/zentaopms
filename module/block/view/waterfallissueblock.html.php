<?php if(empty($issues)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-issues .c-id {width: 55px;}
.block-issues .c-pri {width: 45px; text-align: center;}
.block-issues .c-estimate {width: 60px; text-align: right;}
.block-issues .c-deadline {width: 95px;}
.block-issues .c-status {width: 80px;}
.block-issues.block-sm .c-status {text-align: center;}
</style>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter block-issues <?php if(!$longBlock) echo 'block-sm';?>'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->idAB;?></th>
        <th class='w-100px'><?php echo $lang->issue->type;?></th>
        <th class='c-name'> <?php echo $lang->issue->title;?></th>
        <?php if($longBlock):?>
        <th class='w-80px'><?php echo $lang->issue->severity;?></th>
        <th class='w-80px'><?php echo $lang->issue->pri;?></th>
        <th class='w-80px'><?php echo $lang->issue->owner;?></th>
        <th class='w-80px'><?php echo $lang->issue->assignedTo;?></th>
        <?php endif;?>
        <th class='w-80px'><?php echo $lang->issue->status;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($issues as $issue):?>
      <?php
      $viewLink = $this->createLink('issue', 'view', "issueID={$issue->id}");
      ?>
      <tr>
        <td class='c-id-xs'><?php echo sprintf('%03d', $issue->id);?></td>
        <td class='c-type'><?php echo zget($lang->issue->typeList, $issue->type);?></td>
        <td class='c-name' title='<?php echo $issue->title?>'><?php echo html::a($viewLink, $issue->title);?></td>
        <?php if($longBlock):?>
        <td class='c-severity'><?php echo zget($lang->issue->severityList, $issue->severity, $issue->severity)?></td>
        <td class='c-pri'><?php echo zget($lang->issue->priList, $issue->pri, $issue->pri)?></td>
        <td><?php echo zget($users, $issue->owner, $issue->owner)?></td>
        <td><?php echo zget($users, $issue->assignedTo, $issue->assignedTo)?></td>
        <?php endif;?>
        <td class='c-status'>
          <span class="status-issue status-<?php echo $issue->status?>"><?php echo zget($lang->issue->statusList, $issue->status);?></span>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
