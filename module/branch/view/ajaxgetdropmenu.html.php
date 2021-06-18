<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<div class="list-group">
  <?php
  foreach($branches as $branchID => $branch)
  {
      $selected = $branchID == $currentBranchID ? 'selected' : '';
      $linkHtml = $this->branch->setParamsForLink($module, $link, $projectID, $productID, $branchID);
      echo html::a($linkHtml, $branch, '', "class='$selected' data-key='{$branchesPinyin[$branch]}'");
  }
  ?>
</div>
