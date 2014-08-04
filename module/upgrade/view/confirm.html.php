<?php
/**
 * The html template file of confirm method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: confirm.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <form method='post' action='<?php echo inlink('execute');?>'>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <strong><?php echo $lang->upgrade->confirm;?></strong>
      </div>
      <div class='modal-body'>
        <textarea rows='20' class='form-control' readonly='readonly'><?php echo $confirm;?></textarea>
      </div>
      <div class='modal-footer'><?php echo html::submitButton($lang->upgrade->sureExecute) . html::hidden('fromVersion', $fromVersion);?></div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
