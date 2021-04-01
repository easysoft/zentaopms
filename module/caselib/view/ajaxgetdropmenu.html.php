<div class='list-group' style='max-height: 248px; padding: 5px 10px; margin: 5px 0;'>
  <?php
  $currentLibID = $libID;
  foreach($libraries as $libID => $libName)
  {
      $selected = $currentLibID == $libID ? 'selected' : '';
      echo html::a(sprintf($link, $libID), "<i class='icon-database'></i> " . $libName, '', "class='$selected' data-key='{$librariesPinyin[$libName]}'");
  }
  ?>
</div>
