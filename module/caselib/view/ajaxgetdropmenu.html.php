<div class='list-group'>
  <?php
  foreach($libraries as $libID => $libName)
  {
      echo html::a(sprintf($link, $libID), "<i class='icon-database'></i> " . $libName, '', "data-key='{$librariesPinyin[$libName]}'");
  }
  ?>
</div>
