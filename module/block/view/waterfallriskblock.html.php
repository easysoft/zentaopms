<?php if(empty($risks)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-risks .c-id {width: 40px;}
.block-risks .c-pri {width: 85px; text-align: center;}
.block-risks .c-strategy, .block-risks .c-status {width: 80px;}
.block-risks .c-user, .block-risks .c-rate, .block-risks .c-category {width: 110px;}
.c-rate, .c-category {text-align:center;}
.c-assignedTo {width: 100px; padding:0px !important;text-align:center;}
.pri-low {color: #000000;}
.pri-middle {color: #FF9900;}
.pri-high {color: #E53333;}
</style>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter block-risks <?php if(!$longBlock) echo 'block-sm';?>'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->idAB;?></th>
        <th class='c-name'><?php echo $lang->risk->name;?></th>
        <?php if($longBlock):?>
        <th class='c-strategy'> <?php echo $lang->risk->strategy;?></th>
        <?php endif;?>
        <th class='c-status'><?php echo $lang->risk->status;?></th>
        <?php if($longBlock):?>
        <th class='c-rate'><?php echo $lang->risk->rate;?></th>
        <th class='c-pri'><?php echo $lang->risk->pri;?></th>
        <th class='c-assignedTo text-center'><?php echo $lang->risk->assignedTo;?></th>
        <th class='c-category'><?php echo $lang->risk->category;?></th>
        <?php endif;?>
      </tr>
    </thead>
    <tbody>
      <?php foreach($risks as $risk):?>
      <?php
      $viewLink = $this->createLink('risk', 'view', "riskID={$risk->id}");
      ?>
      <tr>
        <td class='c-id-xs'><?php echo sprintf('%03d', $risk->id);?></td>
        <td class='c-name' title='<?php echo $risk->name?>'><?php echo html::a($viewLink, $risk->name);?></td>
        <?php if($longBlock):?>
        <td class='c-strategy'><?php echo zget($lang->risk->strategyList, $risk->strategy, $risk->strategy)?></td>
        <?php endif;?>
        <td class='c-status' data-text="<?php echo $risk->status;?>">
          <span class="status-risk status-<?php echo $risk->status?>"><?php echo zget($lang->risk->statusList, $risk->status);?></span>
        </td>
        <?php if($longBlock):?>
        <td class='c-rate'><?php echo $risk->rate?></td>
        <td class='c-pri'><?php echo "<span class='pri-{$risk->pri}'>" . zget($lang->risk->priList, $risk->pri) . "</span>";?></td>
        <td class='c-assignedTo'><?php echo zget($users, $risk->assignedTo, $risk->assignedTo)?></td>
        <td class='c-category'><?php echo zget($lang->risk->categoryList, $risk->category, $risk->category)?></td>
        <?php endif;?>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
