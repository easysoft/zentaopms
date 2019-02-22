<div class='libs-group sort'>
  <style>
  .libs-group.sort {padding-left:15px;}
  .libs-group.sort .lib {padding:2px 0 2px 6px;cursor: pointer;}
  </style>
  <?php foreach($libs as $libID => $libName):?>
  <div class='lib' data-id='<?php echo $libID;?>'><i class='icon-move'></i> <?php echo $libName;?></div>
  <?php endforeach;?>
</div>
