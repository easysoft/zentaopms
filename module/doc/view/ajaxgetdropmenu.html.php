<?php js::set('libID', $libID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<input type='text' class='form-control' id='search' value='' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'/>
<div id='searchResult'>
  <div id='defaultMenu' class='search-list'>
    <ul>
    <?php
    foreach($libs as $libID => $libName)
    {
        echo "<li>" . html::a(sprintf($link, $libID), "<i class='icon-cube'></i> " . $libName, '', "class='text-important'"). "</li>";
    }
    ?>
    </ul>
  </div>
</div>
