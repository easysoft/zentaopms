<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<input type='text' class='form-control' id='search' value='' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'/>
<div id='searchResult'>
  <div id='defaultMenu' class='search-list'>
    <ul>
      <?php
      foreach($modules as $moduleID => $module)
      {
          echo "<li data-id='{$moduleID}' data-key='{$modulesPinyin[$module]}'>" . html::a(sprintf($link, $productID, $moduleID), $module, '', "class='text-important'") . "</li>";
      }
      ?>
    </ul>
  </div>
</div>
