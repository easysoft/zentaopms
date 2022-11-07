<?php if(empty($issues)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-issues .c-id {width: 50px;}
.block-issues .c-pri {width: 50px;text-align: center;}
.block-issues .c-category {width: 90px;}
.block-issues .c-identifiedDate {width: 100px;}
.block-issues .c-severity {width: 80px;}
.block-issues .c-status {width: 90px;}
.block-issues.block-sm .c-status {text-align: center;}
</style>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-fixed table-fixed-head table-hover tablesorter block-issues <?php if(!$longBlock) echo 'block-sm'?>'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->idAB?></th>
        <th class='c-name'><?php echo $lang->issue->title?></th>
        <?php if($longBlock):?>
        <th class='c-pri'><?php echo $lang->priAB?></th>
        <th class='c-category'><?php echo $lang->issue->severity;?></th>
        <th class='c-identifiedDate'><?php echo $lang->issue->deadline;?></th>
        <?php endif;?>
        <th class='c-status'><?php echo $lang->issue->status;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($issues as $issue):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      ?>
      <tr>
        <td class='c-id-xs'><?php echo sprintf('%03d', $issue->id);?></td>
        <td class='c-name' title='<?php echo $issue->title?>'><?php echo html::a($this->createLink('issue', 'view', "issueID=$issue->id", '', '', $issue->project), $issue->title)?></td>
        <?php if($longBlock):?>
        <td class='c-pri'><span class="label-pri label-pri-<?php echo $issue->pri;?>"><?php echo zget($lang->issue->priList, $issue->pri)?></span></td>
        <td class='c-severity severity-issue severity-<?php echo $issue->severity;?>'><?php echo zget($lang->issue->severityList, $issue->severity)?></td>
        <td class='c-deadline'><?php echo $issue->deadline == '0000-00-00' ? '' : $issue->deadline;?></td>
        <?php endif;?>
        <?php $status = $this->processStatus('issue', $issue);?>
        <td class='c-status' title='<?php echo $status;?>'>
          <span class="status-issue status-<?php echo $issue->status?>"><?php echo $status;?></span>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
