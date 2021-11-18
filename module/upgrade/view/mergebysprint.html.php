<div class='alert alert-info'>
  <?php
  printf($lang->upgrade->mergeSummary, $noMergedProductCount, $noMergedSprintCount);
  if($type == 'moreLink') echo '<br />' . $lang->upgrade->mergeByProject;
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
        <div class="sprintItem">
          <?php echo html::checkBox("sprints", array($sprint->id => $sprint->name), '', "data-begin='{$sprint->begin}' data-end='{$sprint->end}' data-status='{$sprint->status}' data-pm='{$sprint->PM}'");?>
          <a href='#' id='sprintEdit' class='hidden'><i class="icon-common-edit icon-edit muted"></i></a>
        </div>
        <div class="sprintRename hidden">
          <?php echo html::input("sprintRename_$sprint->id", $sprint->name, "class='form-control'");?>
          <div class="btn-group">
            <button type="button" class="btn btn-success name-confirm"><i class='icon icon-check'></i></button>
            <button type="button" class="btn btn-gray name-cancel"><i class='icon icon-close'></i></button>
          </div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </div>
  <div class='table-col divider strong'><i class='icon icon-angle-double-right'></i></div>
  <div class='table-col pgmWidth' id='programBox'>
    <div class='cell'><?php include "./createprogram.html.php";?></div>
  </div>
</div>
