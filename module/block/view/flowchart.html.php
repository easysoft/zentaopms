<div class='flowchart'>
<?php foreach ($lang->block->flowchart as $flow):?>
  <?php $idx = 0 ?>
  <div class='row'>
  <?php foreach ($flow as $flowItem):?>
    <div class='flow-item flow-item-<?php echo $idx++ ?>'><div><?php echo $flowItem ?></div></div>
  <?php endforeach; ?>
  </div>
<?php endforeach; ?>
</div>
<style>
.flowchart {padding: 10px 15px 1px 10px; min-width: 500px;}
.flow-item {float: left; width: 16.66667%; max-width: 180px; text-align: center; margin-bottom: 9px; padding-right: 5px;}
.flow-item > div {position: relative; padding: 5px 0 5px 12px; line-height: 20px; background: #90A4AE; white-space:nowrap; overflow: visible; color: #fff;}
.flow-item > div:before, .flow-item > div:after {content: ' '; display: block; width: 0; height: 0; border-style: solid; border-width: 15px 0 15px 15px; border-color: transparent transparent transparent #90A4AE; position: absolute; left: 0; top: 0}
.ie-8 .flow-item > div:before {display: none}
.flow-item > div:before {border-left-color: #fff; z-index: 1}
.flow-item > div:after {left: auto; right: -14px; z-index: 2}
.ie-8 .flow-item > div {margin-right: 10px}
.flow-item-0 > div:before {display: none}
.flow-item-1 > div {background: #1976D2}
.flow-item-1 > div:after {border-left-color: #1976D2}
.flow-item-2 > div {background: #4CAF50}
.flow-item-2 > div:after {border-left-color: #4CAF50}
.flow-item-3 > div {background: #F57C00}
.flow-item-3 > div:after {border-left-color: #F57C00}
.flow-item-4 > div {background: #EF5350}
.flow-item-4 > div:after {border-left-color: #EF5350}
.flow-item-5 > div {background: #AB47BC}
.flow-item-5 > div:after {border-left-color: #AB47BC}

.flow-item-1 > div:hover {background: #1565C0}
.flow-item-1 > div:hover:after {border-left-color: #1565C0}
.flow-item-2 > div:hover {background: #43A047}
.flow-item-2 > div:hover:after {border-left-color: #43A047}
.flow-item-3 > div:hover {background: #EF6C00}
.flow-item-3 > div:hover:after {border-left-color: #EF6C00}
.flow-item-4 > div:hover {background: #E53935}
.flow-item-4 > div:hover:after {border-left-color: #E53935}
.flow-item-5 > div:hover {background: #9C27B0}
.flow-item-5 > div:hover:after {border-left-color: #9C27B0}
html[lang="en"] .flowchart {min-width: 700px}
</style>
