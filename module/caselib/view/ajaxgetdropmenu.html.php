<div class='list-group'>
  <?php
  $currentLibID = $libID;
  foreach($libraries as $libID => $libName)
  {
      $selected = $currentLibID == $libID ? 'selected' : '';
      echo html::a(sprintf($link, $libID), "<i class='icon-database'></i> " . $libName, '', "class='$selected' data-key='{$librariesPinyin[$libName]}'");
  }
  ?>
</div>
