<input type='text' class='form-control' id='search' value='' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'/>
<div id='searchResult'>
  <div id='defaultMenu' class='search-list'>
    <ul>
    <?php
    foreach($libraries as $libID => $libName)
    {
        echo "<li data-id='{$libID}'  data-key='{$librariesPinyin[$libName]}'>" . html::a(sprintf($link, $libID), "<i class='icon-database'></i> " . $libName). "</li>";
    }
    ?>
    </ul>
  </div>
</div>
