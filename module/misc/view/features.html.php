<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/carousel.html.php';?>
<main id='features'>
  <header>
    <ul class='nav nav-simple' id='featuresNav'>
      <li class='active'><a class='slide-feature-to' data-slide-to='0' href='#featuresCarousel'><?php echo $lang->misc->feature->introduction; ?></a></li>
      <li><a class='slide-feature-to' data-slide-to='1' href='#featuresCarousel'><?php echo $lang->misc->feature->tutorial; ?></a></li>
      <li><a class='slide-feature-to' data-slide-to='2' href='#featuresCarousel'><?php echo $lang->misc->feature->brandBlueTheme; ?></a></li>
    </ul>
  </header>

  <div id='featuresCarousel' class='carousel slide' data-ride='carousel' data-interval='false'>
    <ol class='carousel-indicators'>
      <li data-target='#featuresCarousel' data-slide-to='0' class='active'></li>
      <li data-target='#featuresCarousel' data-slide-to='1'></li>
      <li data-target='#featuresCarousel' data-slide-to='2'></li>
    </ol>

    <div class='carousel-inner'>
      <div class='item active' style='height: 400px'>
        <div class='article-content'>
          <img src='http://openzui.com/docs/img/slide1.jpg'>
          <div style='position:relative'>
            <p>禅道15系列功能介绍</p>
            <div style='position:absolute;right:0;bottom:0'>
              <a href="###">下载新版本功能介绍文档</a>
            </div>
          </div>
        </div>
      </div>
      <div class='item' style='height: 400px'>
        <img src='http://openzui.com/docs/img/slide2.jpg'>
        <div class='carousel-caption'>
          此页面包含整张图片，文字呈现在图片之上
        </div>
      </div>
      <div class='item' style='height: 400px'>
        <img alt='Third slide' src='http://openzui.com/docs/img/slide3.jpg'>
        <div class='carousel-caption'></div>
      </div>
    </div>
  </div>
  <footer>
    <button type='button' class='btn btn-primary btn-wide slide-feature-to-next btn-slide-next'><?php echo $lang->misc->feature->nextStep; ?></button>
    <button type='button' data-dismiss='modal' class='btn btn-primary btn-wide btn-close-modal'><?php echo $lang->misc->feature->close; ?></button>
  </footer>
</main>
<?php include '../../common/view/footer.lite.html.php';?>
