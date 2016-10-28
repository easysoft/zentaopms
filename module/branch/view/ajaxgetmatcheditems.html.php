<div class='search-list'>
  <ul>
  <?php if(!$branches) echo "<li class='no-result-tip'>" . sprintf($lang->product->noMatched, $keywords) . '</li>';?>
  <?php
  foreach($branches as $branchID => $branch)
  {
      echo "<li>" . html::a(sprintf($link, $productID, $branchID), "<i class='icon-cube'></i> " . $branch). "</li>";
  }
  ?>
  </ul>
</div>
