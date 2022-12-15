<div class='flowchart'>
<?php foreach ($lang->block->flowchart as $flowName => $flow):?>
<?php $idx = 0; ?>
  <div class='row row-<?php echo $flowName?>'>
  <?php foreach ($flow as $flowItem):?>
    <div class='flow-item flow-item-<?php echo $idx ++ ?>'><div title='<?php echo $flowItem ?>'><span class='flow-item-display'><?php echo $flowItem ?></span></div></div>
  <?php endforeach; ?>
  </div>
<?php endforeach; ?>
</div>
<style>
.flow-item {float: left; width: 16.66667%; max-width: 180px; text-align: center; margin-bottom: 9px; padding-right: 15px;}
.flow-item > div {position: relative; padding: 5px 0 5px 8px; line-height: 20px; background: #66A2FF; white-space:nowrap; overflow: visible; color: #3c4353}
.flow-item > div:before, .flow-item > div:after {content: ' '; display: block; width: 0; height: 0; border-style: solid; border-width: 15px 0 15px 10px; border-color: transparent transparent transparent #66A2FF; position: absolute; left: 0; top: 0;}
.ie-8 .flow-item > div:before {display: none}
.flow-item > div:before {border-left-color: #fff; z-index: 1}
.flow-item > div:after {left: auto; right: -10px; z-index: 2}
.flow-item > div > span {display: flow-root; overflow: hidden;}
.ie-8 .flow-item > div {margin-right: 10px}
.flow-item-0 > div {color: #FFF; font-weight: 400; padding-left: 0;}
.flow-item-0 > div:before {display: none}
.flow-item-1 > div,
.flow-item-2 > div,
.flow-item-3 > div,
.flow-item-4 > div,
.flow-item-5 > div {background: #E6F0FF}
.flow-item-1 > div:after,
.flow-item-2 > div:after,
.flow-item-3 > div:after,
.flow-item-4 > div:after,
.flow-item-5 > div:after {border-left-color: #E6F0FF}

.block-sm .flow-item {padding-right: 5px}
.block-sm .flow-item > div:before, .block-sm .flow-item > div:after {border-width: 15px 0 15px 6px;}
.block-sm .row-3 .flow-item-1, .block-sm .row-3 .flow-item-3 {width: 25%}
.block-sm .flow-item > div:after {right: -6px;}

.flow-item-display {overflow: hidden; white-space:nowrap; text-overflow: clip;}
</style>
