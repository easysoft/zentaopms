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
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php common::printLink('custom', 'concept', '', "<span class='text'>{$lang->custom->concept}</span>", '', "class='btn btn-link btn-active-text'"); ?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form id='ajaxForm' class='form-ajax' method='post'>
    <div class='modal-body'>
      <div class="form-group">
        <label><?php echo $lang->custom->conceptQuestions['overview']?></label>
        <div class="checkbox"> <?php echo html::radio('productProject', $lang->custom->productProject->relation, zget($this->config->custom, 'productProject', '0_0'))?> </div>
      </div>
      <div class="form-group">
        <label></label>
        <div><?php echo html::submitButton();?></div>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
