<div class='panel-body scrollbar-hover'>
<?php foreach ($lang->block->flowchart as $flowName => $flow):?>
<?php $idx = 0; ?>
  <div class='row row-<?php echo $flowName?>'>
  <?php foreach ($flow as $flowItem):?>
    <div class='flow-item flow-item-<?php echo $idx++ ?>'><div title='<?php echo $flowItem ?>'><?php echo $flowItem ?></div></div>
  <?php endforeach; ?>
  </div>
<?php endforeach; ?>
</div>
<style>
.block-flowchart .panel-body {padding: 0 30px 0 20px;}
.flow-item {float: left; width: 16.66667%; max-width: 180px; text-align: center; margin-bottom: 9px; padding-right: 15px;}
.flow-item > div {position: relative; padding: 5px 0 5px 8px; line-height: 20px; background: #E8EBEF; white-space:nowrap; overflow: visible; color: #3c4353}
.flow-item > div:before, .flow-item > div:after {content: ' '; display: block; width: 0; height: 0; border-style: solid; border-width: 15px 0 15px 10px; border-color: transparent transparent transparent #E8EBEF; position: absolute; left: 0; top: 0;}
.ie-8 .flow-item > div:before {display: none}
.flow-item > div:before {border-left-color: #fff; z-index: 1}
.flow-item > div:after {left: auto; right: -10px; z-index: 2}
.ie-8 .flow-item > div {margin-right: 10px}
.flow-item-0 > div {color: #838A9D; font-weight: bold; padding-left: 0;}
.flow-item-0 > div:before {display: none}
.flow-item-1 > div {background: #E3F2FD}
.flow-item-1 > div:after {border-left-color: #E3F2FD}
.flow-item-2 > div {background: #E8F5E9}
.flow-item-2 > div:after {border-left-color: #E8F5E9}
.flow-item-3 > div {background: #FFF3E0}
.flow-item-3 > div:after {border-left-color: #FFF3E0}
.flow-item-4 > div {background: #FFEBEE}
.flow-item-4 > div:after {border-left-color: #FFEBEE}
.flow-item-5 > div {background: #F3E5F5}
.flow-item-5 > div:after {border-left-color: #F3E5F5}
.flow-item-1 > div:hover {background: #1565C0; color: #fff;}
.flow-item-1 > div:hover:after {border-left-color: #1565C0}
.flow-item-2 > div:hover {background: #43A047; color: #fff;}
.flow-item-2 > div:hover:after {border-left-color: #43A047}
.flow-item-3 > div:hover {background: #EF6C00; color: #fff;}
.flow-item-3 > div:hover:after {border-left-color: #EF6C00}
.flow-item-4 > div:hover {background: #E53935; color: #fff;}
.flow-item-4 > div:hover:after {border-left-color: #E53935}
.flow-item-5 > div:hover {background: #9C27B0; color: #fff;}
.flow-item-5 > div:hover:after {border-left-color: #9C27B0}

.block-sm .flow-item {padding-right: 5px}
.block-sm .flow-item > div:before, .block-sm .flow-item > div:after {border-width: 15px 0 15px 6px;}
.block-sm .row-3 .flow-item-1, .block-sm .row-3 .flow-item-3 {width: 25%}
.block-sm .flow-item > div:after {right: -6px;}
</style>
