<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<div class="list-group">
  <?php
  foreach($branches as $branchID => $branch)
  {
      echo html::a(sprintf($link, $productID, $branchID), "<i class='icon-cube'></i> " . $branch, '', "data-key='{$branchesPinyin[$branch]}'");
  }
  ?>
</div>
