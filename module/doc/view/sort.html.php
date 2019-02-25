<div class='libs-group sort'>
  <style>
  .side-col .tabs .tab-content{overflow-x:hidden;}
  .libs-group.sort {padding-left:15px;}
  .libs-group.sort .lib {padding:4px 0 4px 6px;cursor: pointer; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
  </style>
  <?php foreach($libs as $libID => $libName):?>
  <div class='lib' data-id='<?php echo $libID;?>'><i class='icon-move'></i> <?php echo $libName;?></div>
  <?php endforeach;?>
</div>
