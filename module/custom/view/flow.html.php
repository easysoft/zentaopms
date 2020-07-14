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
<?php if(isset($this->config->conceptSetted)):?>
<?php include 'header.html.php';?>
<?php else:?>
<?php include '../../common/view/header.lite.html.php';?>
<?php endif;?>
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
  <form id='ajaxForm' method='post'>
    <div class='modal-body'>
      <div class="form-group">
        <label><?php echo $lang->custom->conceptQuestions['overview']?></label>
        <div class="checkbox"> <?php echo html::radio('productProject', $lang->custom->productProject->relation, zget($this->config->custom, 'productProject', empty($this->config->isINT) ? '0_0' : '0_1'))?> </div>
      </div>
      <?php if(!common::checkNotCN()):?>
      <div class="form-group">
        <label><?php echo $lang->custom->conceptQuestions['story']?></label>
        <div class="checkbox"> <?php echo html::radio('storyRequirement', $lang->custom->conceptOptions->story, zget($this->config->custom, 'storyRequirement', '0'));?></div>
      </div>
      <?php endif;?>
      <div class="form-group">
        <label id='requirementpoint'><?php echo $lang->custom->conceptQuestions['requirementpoint'];?></label>
        <label id='storypoint'><?php echo $lang->custom->conceptQuestions['storypoint'];?></label>
        <div class="checkbox"> <?php echo html::radio('hourPoint', $lang->custom->conceptOptions->hourPoint, zget($this->config->custom, 'hourPoint'))?> </div>
      </div>
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
