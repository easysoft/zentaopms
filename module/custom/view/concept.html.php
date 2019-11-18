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
include '../../common/view/header.lite.html.php';
?>
<?php if($this->config->conceptSetted):?>
<?php include '../../common/view/header.html.php';?>
<?php else:?>
<?php include '../../common/view/header.lite.html.php';?>
<?php endif;?>
<div class='container'>
<?php if($_POST):?>
  <div class='modal-dialog'>
    <div class='alert alert-success'>
    <?php printf($lang->custom->notice->conceptResult, $lang->productCommon, $lang->projectCommon, $lang->storyCommon, $lang->hourCommon);?>
    </div>
  </div>
<?php else:?>
  <form class="load-indicator main-form form-ajax" id='dataform' method='post'>
    <div class='modal-dialog'>
      <div class='modal-header'><strong><?php echo $lang->custom->concept;?></strong></div>
          <div class='modal-body'>
            <div class="form-group">
              <label><?php echo $lang->custom->conceptQuestions['overview']?></label>
              <div class="checkbox"> <?php echo html::radio('productProject', $lang->custom->productProject->relation, zget($this->config->custom, 'productProject', '0_0'))?> </div>
            </div>
            <?php if(!common::checkNotCN()):?>
            <div class="form-group">
              <label><?php echo $lang->custom->conceptQuestions['story']?></label>
              <div class="checkbox"> <?php echo html::radio('storyRequirement', $lang->custom->conceptOptions->story, zget($this->config->custom, 'storyRequirement', '0'));?></div>
            </div>
            <?php endif;?>
            <div class="form-group">
              <label><?php echo $lang->custom->conceptQuestions['storypoint'];?></label>
              <div class="checkbox"> <?php echo html::radio('hourPoint', $lang->custom->conceptOptions->hourPoint, '0')?> </div>
            </div>
          </div>
      <div class='modal-footer'>
        <div class='text-center'><?php echo html::submitButton();?></div>
      </div>
    </div>
  </form>
<?php endif;?>
<?php if($this->config->conceptSetted):?>
<?php include '../../common/view/footer.html.php';?>
<?php else:?>
</div>
</body>
</html>
<?php endif;?>
