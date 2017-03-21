<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<input type='text' class='form-control' id='search' value='' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'/>
<div id='searchResult'>
  <div id='defaultMenu' class='search-list'>
    <ul>
    <?php
    foreach($branches as $branchID => $branch)
    {
        echo "<li data-id='{$branchID}' data-key='{$branchesPinyin[$branch]}'>" . html::a(sprintf($link, $productID, $branchID), "<i class='icon-cube'></i> " . $branch, '', "class='text-important'"). "</li>";
    }
    ?>
    </ul>
  </div>
</div>
