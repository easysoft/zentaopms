<?php include $app->getModuleRoot() . 'transfer/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header clearfix">
    <h2><?php echo $lang->transfer->import;?></h2>
  </div>
  <form class='main-form' target='hiddenwin' method='post'>
    <table class='table table-form' id='showData'>
      <thead>
        <tr>
        <?php include $app->getModuleRoot() . 'transfer/view/thead.html.php';?>
        </tr>
      </thead>
      <tbody>

      </tbody>
      <tfoot class='hidden'>
        <?php include $app->getModuleRoot() . 'transfer/view/tfoot.html.php';?>
      </tfoot>
    </table>
    <?php if(!$this->session->insert); include $app->getModuleRoot() . 'common/view/noticeimport.html.php';?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'transfer/view/footer.html.php';?>
