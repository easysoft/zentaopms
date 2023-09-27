<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/carousel.html.php';?>
<?php js::set('features', $features);?>
<main id='features' class="is-first-item">
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
      <div class='item <?php echo $key == 0 ? 'active' : '';?>' style='height: 460px'>
        <?php if($feature == 'aiPrompts'): ?>
            <div class='text-center'>
                <img class='text-center' src='<?php echo $config->webRoot . $lang->misc->feature->aiPromptsImage; ?>'/>
            </div>
        <?php elseif($feature == 'promptDesign'): ?>
            <div class='text-center'>
                <img class='text-center' src='<?php echo $config->webRoot . $lang->misc->feature->promptDesignImage; ?>'/>
            </div>
        <?php elseif($feature == 'promptExec'): ?>
            <div class='text-center'>
                <img class='text-center' src='<?php echo $config->webRoot . $lang->misc->feature->promptExecImage; ?>'/>
            </div>
        <?php endif; ?>
      </div>
      <?php endforeach;?>
    </div>
  </div>
  <footer style='display: flex; gap: 16px; justify-content: center; padding: 24px 0 24px 0;'>
    <button type='button' class='btn btn-wide slide-feature-to-prev btn-slide-prev'><?php echo $lang->misc->feature->prevStep; ?></button>
    <button type='button' class='btn btn-primary btn-wide slide-feature-to-next btn-slide-next'><?php echo $lang->misc->feature->nextStep; ?></button>
    <button type='button' data-dismiss='modal' class='btn btn-primary btn-wide btn-close-modal'><?php echo $lang->misc->feature->close; ?></button>
    <a class='btn-close-modal' href='<?php echo $lang->misc->feature->promptLearnMore;?>' target='_blank'><button type='button' data-dismiss='modal' class='btn btn-info btn-wide' style='background: none;'><?php echo $lang->misc->feature->learnMore; ?></button></a>
  </footer>
</main>
<?php include '../../common/view/footer.lite.html.php';?>
