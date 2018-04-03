<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<div class="input-control search-box search-box-circle has-icon-left has-icon-right search-example">
  <input type="search" class="form-control search-input">
  <label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
  <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
</div>
<div class="list-group">
  <?php
  foreach($branches as $branchID => $branch)
  {
      echo html::a(sprintf($link, $productID, $branchID), "<i class='icon-cube'></i> " . $branch, '', "data-filter='{$branchesPinyin[$branch]}'");
  }
  ?>
</div>
