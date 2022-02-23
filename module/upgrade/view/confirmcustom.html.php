<?php
/**
 * The confirmcustom view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: confirmcustom.html.php 4129 2022-02-23 10:20:14Z $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <form method='post' action='<?php echo inlink('moveEXTFiles', "fromVersion=$fromVersion");?>'>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <strong><?php echo $lang->upgrade->confirmCustomTip;?></strong>
      </div>
      <div class='modal-footer'>
        <?php echo html::submitButton($lang->upgrade->yes);?>
        <?php echo html::a(inlink('afterExec', "fromVersion=$fromVersion&processed=no&skipConfirm=yes"), $lang->upgrade->no, '', "class='btn btn-back btn-wide'");?>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
