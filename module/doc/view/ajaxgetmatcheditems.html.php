<div class='search-list'>
  <ul>
  <?php if(!$libs) echo "<li class='no-result-tip'>" . sprintf($lang->doc->noMatched, $keywords) . '</li>';?>
  <?php
  foreach($libs as $lib)
  {
      echo "<li>" . html::a(sprintf($link, $lib->id), "<i class='icon-cube'></i> " . $lib->name, ''). "</li>";
  }
  ?>
  </ul>
</div>
