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
<?php include 'header.html.php';?>
<?php if(isset($this->config->conceptSetted)):?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <div class='heading'>
      <strong><?php echo $lang->custom->flow?></strong>
    </div>
  </div>
<?php else:?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-header'>
      <strong><?php echo $lang->custom->flow?></strong>
    </div>
<?php endif;?>
  <form class='form-ajax' method='post'>
    <div class='modal-body'>
      <div class="form-group">
        <label><?php echo $lang->custom->conceptQuestions['overview']?></label>
        <div class="checkbox"> <?php echo html::radio('sprintConcept', $lang->custom->sprintConceptList, zget($this->config->custom, 'sprintConcept', '0'))?> </div>
      </div>
      <div class="form-group">
        <label><?php echo $lang->custom->conceptQuestions['URAndSR']?></label>
        <div class="checkbox"> <?php echo html::radio('URAndSR', $lang->custom->conceptOptions->URAndSR, zget($this->config->custom, 'URAndSR', '0'));?></div>
      </div>
      <?php if(!isset($config->maxVersion)):?>
      <div class="form-group">
        <label id='storypoint'><?php echo $lang->custom->conceptQuestions['storypoint'];?></label>
        <div class="checkbox"> <?php echo html::radio('hourPoint', $lang->custom->conceptOptions->hourPoint, zget($this->config->custom, 'hourPoint'))?> </div>
      </div>
      <?php endif;?>
      <div class="form-group">
        <label></label>
        <div><?php echo html::submitButton();?></div>
      </div>
    </div>
  </form>
<?php if(isset($this->config->conceptSetted)):?>
<?php include '../../common/view/footer.html.php';?>
<?php else:?>
<?php echo js::execute($pageJS);?>
</div>
</div>
</body>
</html>
<?php endif;?>
