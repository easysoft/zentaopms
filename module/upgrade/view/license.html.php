<?php
/**
 * The html file of license method of upgrade module of ZentaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: selectversion.html.php 1292 2014-06-05 03:03:52Z guanxiying $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog modal-lg'>
    <div class='modal-header'>
      <h3><?php echo $lang->upgrade->license;?></h3>
    </div>
    <div class='modal-body'>
      <?php echo html::textarea('license', $license, "class='form-control mgb-10' rows='12'")?>
      <div class='text-left mgb-10'>
        <label class='checkbox-inline'><input type='checkbox' id='agree' checked='checked' /><?php echo $lang->agreement;?></label>
      </div>
    </div>
    <div class='modal-footer'>
      <?php echo html::a(inlink('license', 'agree=true'), $lang->confirm, '', "class='btn btn-primary btn-install'");?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
