<?php if(empty($risks)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-risks .c-id {width: 50px;}
.block-risks .c-pri {width: 50px;text-align: center;}
.block-risks .c-plannedClosedDate {width: 100px;}
.block-risks .c-category {width: 80px;}
.block-risks .c-status {width: 80px;}
.block-risks.block-sm .c-status {text-align: center;}
.pri-low {color: #000000;}
.pri-middle {color: #FF9900;}
.pri-high {color: #E53333;}
</style>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-fixed table-fixed-head table-hover tablesorter block-risks <?php if(!$longBlock) echo 'block-sm'?>'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->idAB?></th>
        <th class='c-name'><?php echo $lang->risk->name?></th>
        <?php if($longBlock):?>
        <th class='c-pri'><?php echo $lang->priAB?></th>
        <th class='c-category'><?php echo $lang->risk->category;?></th>
        <th class='c-plannedClosedDate'><?php echo $lang->risk->plannedClosedDate;?></th>
        <?php endif;?>
        <th class='c-status'><?php echo $lang->risk->status;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($risks as $risk):?>
      <?php
      $appid = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      ?>
      <tr>
        <td class='c-id-xs'><?php echo sprintf('%03d', $risk->id);?></td>
        <td class='c-name' title='<?php echo $risk->name?>'><?php echo html::a($this->createLink('risk', 'view', "riskID=$risk->id", '', '', $risk->project), $risk->name)?></td>
        <?php if($longBlock):?>
        <td class='c-pri'><?php echo "<span class='pri-{$risk->pri}'>" . zget($lang->risk->priList, $risk->pri) . "</span>";?></td>
        <td class='c-category'><?php echo zget($lang->risk->categoryList, $risk->category)?></td>
        <td class='c-plannedClosedDate'><?php echo $risk->plannedClosedDate == '0000-00-00' ? '' : $risk->plannedClosedDate;?></td>
        <?php endif;?>
        <?php $status = $this->processStatus('risk', $risk);?>
        <td class='c-status' title='<?php echo $status;?>'>
          <span class="status-risk status-<?php echo $risk->status?>"><?php echo $status;?></span>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
