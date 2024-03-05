<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/carousel.html.php';?>
<?php js::set('features', $features);?>
<main id='features'>
  <header>
    <ul class='nav nav-simple' id='featuresNav'>
      <?php foreach($features as $key => $feature): ?>
      <li <?php echo $key == 0 ? "class='active'" : '';?>><a class='slide-feature-to' data-slide-to='<?php echo $key;?>' href='#featuresCarousel'><?php echo $lang->misc->feature->$feature; ?></a></li>
      <?php endforeach;?>
    </ul>
  </header>

  <div id='featuresCarousel' class='carousel slide' data-ride='carousel' data-interval='false'>
    <ol class='carousel-indicators'>
      <?php if(count($features) > 1):?>
      <?php foreach($features as $key => $feature): ?>
      <li data-target='#featuresCarousel' data-slide-to='<?php echo $key;?>' <?php echo $key == 0 ? "class='active'" : ''?>></li>
      <?php endforeach;?>
      <?php endif;?>
    </ol>

    <div class='carousel-inner'>
      <?php foreach($features as $key => $feature): ?>
      <div class='item <?php echo $key == 0 ? 'active' : '';?>' style='height: 395px'>
        <?php if($feature == 'introduction'):?>
        <div class='article-content text-center'>
          <video src="<?php echo $lang->install->guideVideo;?>" height="320px" controls ="controls" id='guideVideo'></video>
          <div class='text-center' style='position:relative'>
            <p><?php echo $lang->install->introduction;?></p>
            <div class='download-file'>
              <a href='https://dl.zentao.net/zentao/zentaoconcept.pdf' target='_blank'><?php echo $lang->misc->feature->downloadFile;?></a>
            </div>
          </div>
        </div>
        <?php elseif($feature == 'tutorial'):?>
        <div class='text-center'>
          <?php echo $lang->misc->feature->tutorialDesc;?>
          <img class='text-center' src='<?php echo $config->webRoot . $lang->misc->feature->tutorialImage;?>' />
        </div>
        <?php elseif($feature == 'youngBlueTheme'):?>
        <div class='text-center'>
          <?php echo $lang->misc->feature->themeDesc;?>
          <img class='text-center' src='<?php echo $config->webRoot . $lang->misc->feature->youngBlueImage;?>' />
        </div>
        <?php elseif($feature == 'visions'):?>
        <div class='text-center'>
          <?php echo $lang->misc->feature->visionsDesc;?>
          <img class='text-center' src='<?php echo $config->webRoot . $lang->misc->feature->visionsImage;?>' />
        </div>
        <?php endif;?>
      </div>
      <?php endforeach;?>
    </div>
  </div>
  <footer>
    <button type='button' class='btn btn-primary btn-wide slide-feature-to-next btn-slide-next'><?php echo $lang->misc->feature->nextStep; ?></button>
    <button type='button' data-dismiss='modal' class='btn btn-primary btn-wide btn-close-modal'><?php echo $lang->misc->feature->close; ?></button>
  </footer>
</main>
<?php include '../../common/view/footer.lite.html.php';?>
