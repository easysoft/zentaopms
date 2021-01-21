<?php include '../../common/view/header.lite.html.php';?>
<main id="main">
  <div id='versionContent' class="container">
    <div class='main-content'>
      <div class='center-block mw-800px'>
        <div id="featureList" class="article-content">
          <div class="item">
            <h3><?php echo $version->explain;?></h3>
            <div class="desc"></div>
          </div>
        </div>
        <?php if($version->log):?>
        <div id="details" class='article-content'>
          <a href='###' class="btn btn-link text-primary btn-block text-left" onclick="$('#details').toggleClass('show-details')"><i class="icon icon-angle-right"></i> <?php echo $lang->index->detailed;?></a>
          <div class='details-list'><?php echo $version->log;?></div>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</main>
<?php include '../../common/view/footer.lite.html.php';?>
