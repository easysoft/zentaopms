<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<div class="list-group">
  <?php
  foreach($modules as $moduleID => $module)
  {
      if(empty($module))
      {
          $module = '/';
          $modulesPinyin[$module] = '';
      }
      echo html::a(sprintf($link, $productID, $moduleID), $module, '', "data-key='{$modulesPinyin[$module]}'");
  }
  ?>
</div>
