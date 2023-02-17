<div class='flowchart'>
<?php foreach ($lang->block->flowchart as $flowName => $flow):?>
<?php $idx = 0; ?>
  <div class='row flow-chart-row row-<?php echo $flowName?>'>
  <?php foreach ($flow as $flowItem):?>
    <div class='flow-item flow-item-<?php echo $idx ++ ?>'><div title='<?php echo $flowItem ?>'><div class='flow-item-display'><?php echo $flowItem ?></div></div></div>
  <?php endforeach; ?>
  </div>
<?php endforeach; ?>
</div>
<style>
.flow-chart-row {display: flex; gap: 5px; margin-bottom: 9px; width: 100%;}
.flow-item {background: url('static/svg/arrow-blue.svg') center no-repeat; background-size: cover; text-align: center; margin: 0; padding-right: 15px; flex:0 1 15%; position: relative; padding: 5px 0 5px 8px; white-space:nowrap; overflow: hidden; color: #3c4353; display: flex; align-items: center; text-align: center; padding-right: 10px;}
.ie-8 .flow-item > div:before {display: none}
.flow-item > div:before {border-left-color: #fff; z-index: 1}
.flow-item > div > div {display: flow-root; overflow: hidden;}
.ie-8 .flow-item > div {margin-right: 10px}
.flow-item-0 > div {color: #FFF; font-weight: 400;}
.flow-item-0 > div:before {display: none}

.flow-item-1,
.flow-item-2,
.flow-item-3,
.flow-item-4,
.flow-item-5 {background-image: url('static/svg/arrow-white.svg'); }

.block-sm .flow-item {padding-right: 5px}
.block-sm .flow-item > div:before, .block-sm .flow-item > div:after {border-width: 15px 0 15px 6px;}
.block-sm .row-3 .flow-item-1, .block-sm .row-3 .flow-item-3 {width: 25%}
.block-sm .flow-item > div:after {right: -6px;}

.flow-item-display {overflow: hidden; white-space:nowrap; text-overflow: clip; font-size: 12px;}
</style>
