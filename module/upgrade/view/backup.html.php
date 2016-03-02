<?php
/**
 * The html template file of index method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: index.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-header'>
      <strong><?php echo $lang->upgrade->warnning;?></strong>
    </div>
    <div class='modal-body'>
      <div class='alert alert-pure'>
        <div class='content'><?php echo $lang->upgrade->warnningContent;?></div>
      </div>
    </div>
    <div class='modal-footer'><?php echo html::linkButton($lang->upgrade->common, inlink('checkExtension'), 'self', '', 'btn btn-primary');?></div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
