<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <form class="load-indicator main-form form-ajax" method='post'>
      <div class='modal-body'>
        <div class='form-group'>
          <label><?php echo $lang->custom->waterfall->plan;?></label>
          <div class='checkbox'><?php echo html::radio('planStatus', $lang->custom->waterfallOptions->planStatus, $status);?></div>
        </div>
        <div class='form-group'>
          <label><?php echo $lang->custom->waterfall->URAndSR;?></label>
          <div class='checkbox'><?php echo html::radio('URAndSR', $lang->custom->waterfallOptions->URAndSR, zget($this->config->custom, 'URAndSR', '0'));?></div>
        </div>
        <?php $hidden = zget($this->config->custom, 'URAndSR', 0) == 0 ? 'hidden' : '';?>
        <div class="form-group <?php echo $hidden;?>" id='URSRName'><label><?php echo $lang->custom->waterfall->URSRName;?></label>
          <div class='input-group'>
            <?php 
              $URSRName = isset($this->config->custom->URSRName) ? json_decode($this->config->custom->URSRName, true) : array();
              echo html::input("urCommon[{$clientLang}]", isset($URSRName['urCommon'][$clientLang]) ? $URSRName['urCommon'][$clientLang] : $lang->custom->URStory, "class='form-control'");
            ?>
            <span class='input-group-addon'></span>
            <?php echo html::input("srCommon[{$clientLang}]", isset($URSRName['srCommon'][$clientLang]) ? $URSRName['srCommon'][$clientLang] : $lang->custom->SRStory, "class='form-control'");?>
          </div>
        </div>
        <div class='form-group'>
          <div><?php echo html::submitButton();?></div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
