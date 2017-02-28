<div class='search-list'>
  <ul>
  <?php if(!$libraries) echo "<li class='no-result-tip'>" . sprintf($lang->product->noMatched, $keywords) . '</li>';?>
  <?php
  foreach($libraries as $lib)
  {
      echo "<li>" . html::a(sprintf($link, $lib->id), "<i class='icon-cube'></i> " . $lib->name) . "</li>";
  }
  ?>
  </ul>
</div>
