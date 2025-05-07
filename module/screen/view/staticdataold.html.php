<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<script type="module" crossorigin src="<?php echo $webRoot;?>static/js/index.js"></script>
<div id="appProvider" style="display: none;"></div>
<div id="app">
  <div class="first-loading-wrp">
    <div class="loading-wrp">
      <span class="dot dot-spin"></span>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
