<div class='alert alert-info'>
  <?php
  printf($lang->upgrade->mergeSummary, $noMergedProductCount, $noMergedSprintCount);
  echo '<br />' . $lang->upgrade->mergeByProject;
  ?>
</div>
<div class='main-row'>
  <div class='table-col' id='source'>
    <div class='cell'>
      <div class='lineGroup-title'>
        <div class='item checkbox-primary' title="<?php echo $lang->selectAll?>">
          <input type='checkbox' id='checkAllProjects'><label for='checkAllProjects'><strong><?php echo $lang->projectCommon;?></strong></label>
        </div>
      </div>
      <div class='line-groups sprintGroup'>
        <?php foreach($noMergedSprints as $sprintID => $sprint):?>
        <?php echo html::checkBox("sprints", array($sprint->id => $sprint->name), '', "data-begin='{$sprint->begin}' data-end='{$sprint->end}' data-status='{$sprint->status}' data-pm='{$sprint->PM}'");?>
        <?php endforeach;?>
      </div>
    </div>
  </div>
  <div class='table-col divider strong'><i class='icon icon-angle-double-right'></i></div>
  <div class='table-col pgmWidth' id='programBox'>
    <div class='cell'><?php include "./createprogram.html.php";?></div>
  </div>
</div>
