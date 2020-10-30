<style> .sprint-group{padding: 10px 0px}</style>
<div class='alert alert-info'>
  <?php
  printf($lang->upgrade->mergeSummary, $noMergedProductCount, $noMergedSprintCount);
  echo '<br />' . $lang->upgrade->mergeByProject;
  ?>
</div>
<div class='main-row'>
  <div class='table-col' id='source'>
    <div class='cell'>
      <div class='item'><strong><?php echo $lang->upgrade->project;?></strong></div>
      <div class='sprint-group'>
        <?php foreach($noMergedSprints as $sprintID => $sprint):?>
        <tr>
          <td><?php echo html::checkBox("sprints", array($sprint->id => "{$lang->upgrade->project} #{$sprint->id} {$sprint->name}"), $sprint->id, "data-begin='{$sprint->begin}' data-end='{$sprint->end}'");?></td>
        </tr>
        <?php endforeach;?>
      </div>
    </div>
  </div>
  <div class='table-col divider strong'><i class='icon icon-angle-double-right'></i></div>
  <div class='table-col pgmWidth' id='programBox'>
    <div class='cell'><?php include "./createprogram.html.php";?></div>
  </div>
</div>
