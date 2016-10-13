<div class='search-list'>
  <ul>
  <?php if(!$products) echo "<li class='no-result-tip'>" . sprintf($lang->product->noMatched, $keywords) . '</li>';?>
  <?php
  foreach($products as $product)
  {
      echo "<li>" . html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "class='$product->status'"). "</li>";
  }
  ?>
  </ul>
</div>
