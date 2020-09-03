<?php
/**
 * The html template file of deny method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: deny.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <form id='ajaxForm' class='form-ajax' method='post'>
    <div class='modal-body'>
      <div class="form-group">
        <label><?php echo $lang->custom->conceptQuestions['overview']?></label>
        <div class="checkbox"> <?php echo html::radio('productProject', $lang->custom->productProject->relation, zget($this->config->custom, 'productProject', '0_0'))?> </div>
      </div>
      <div class='form-group'>
        <label><?php echo '2. ' . $lang->custom->waterfall->URAndSR;?></label>
        <div class='checkbox'><?php echo html::radio('URAndSR', $lang->custom->waterfallOptions->URAndSR, zget($this->config->custom, 'URAndSR', '0'));?></div>
      </div>
      <?php $hidden = zget($this->config->custom, 'URAndSR', 0) == 0 ? 'hidden' : '';?>
      <div class="form-group <?php echo $hidden;?>" id='URSRName'><label><?php echo '3. ' . $lang->custom->waterfall->URSRName;?></label>
        <div class='input-group'>
          <?php 
            $URSRName = isset($this->config->custom->URSRName) ? json_decode($this->config->custom->URSRName, true) : array();
            echo html::input("urCommon[{$clientLang}]", isset($URSRName['urCommon'][$clientLang]) ? $URSRName['urCommon'][$clientLang] : $lang->custom->URStory, "class='form-control'");
          ?>
          <span class='input-group-addon'></span>
          <?php echo html::input("srCommon[{$clientLang}]", isset($URSRName['srCommon'][$clientLang]) ? $URSRName['srCommon'][$clientLang] : $lang->custom->SRStory, "class='form-control'");?>
        </div>
      </div>
      <div class="form-group">
        <label></label>
        <div><?php echo html::submitButton();?></div>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
